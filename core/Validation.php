<?php

namespace App\core;

use App\core\exceptions\NotFoundException;

class Validation
{
    public $errors = [];

    /**
     * Lista delle regole di validazione, con i rispettivi messaggi di errore.
     */
    protected $availableRules = [
        'required' => 'Il campo :field: è obbligatorio.',
        'email' => 'Il campo :field: deve contenere un indirizzo email valido.',
        'alpha_dash' => 'Il campo :field: può contenere solo lettere, numeri, trattini e underscore.',
        'unique' => 'Il campo :field: è già presente nella tabella {table}.',
        'exists' => 'Il campo :field: non è presente nel database.',
        'min' => 'Il campo :field: non può essere di lunghezza inferiore a {min} caratteri.',
        'max' => 'Il campo :field: non può essere di lunghezza superiore a {max} caratteri.',
        'match' => 'Il campo :field: deve essere uguale al campo {match}.',
        'letter' => 'Il campo :field: deve contenere almeno una lettera.',
        'number' => 'Il campo :field: deve contenere almeno un numero.',
        'upper' => 'Il campo :field: deve contenere almeno una lettera maiuscola.',
        'special_char' => 'Il campo :field: deve contenere almeno un carattere speciale. !#$%&?@_'
    ];

    /**
     * @param array $data
     * @param array $rules
     * @param string $url
     * @return array|bool
     */
    public function validate(array $data, array $rules, string $url): array |bool
    {
        $sanitizedData = $this->sanitize($data);

        // Check che tutte le regole passate dall'utente siano presenti nell'array delle availableRules
        $this->ruleExists($rules);

        // Eseguo i controlli per ogni campo, e se ci sono degli errori, li aggiungo all'array errors
        $this->validateFields($rules, $sanitizedData);

        // Se ci sono errori  
        if (!empty($this->errors)) {

            Application::$app->response->redirect($url)
                ->withValidationErrors($this->errors)
                ->withOldData($data);

            exit;
        }

        return $data;
    }

    /**
     * @param array $data
     * @return 
     */
    protected function sanitize(array $data): array
    {
        foreach ($data as $key => $value) {
            $data[$key] = stripslashes(trim(filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS)));
        }

        return $data;
    }

    /**
     * @param array $rules
     * @param array $data
     * @return void
     */
    protected function validateFields(array $rules, array $data): void
    {
        foreach ($rules as $field => $validationRules) {

            $fieldValue = [
                //  "username" => "valore input",
                //  "rules"    => "required,alpha_dash"
                $field => $data[$field],
                'rules' => implode(',', $validationRules)
            ];

            // Rule email
            if (str_contains($fieldValue['rules'], 'email') && !filter_var($fieldValue[$field], FILTER_VALIDATE_EMAIL)) {
                $this->addError($field, 'email');
            }

            // Rule alpha_dash
            if (str_contains($fieldValue['rules'], 'alpha_dash') && !preg_match('/^[a-zA-Z0-9_-]*$/', $fieldValue[$field])) {
                $this->addError($field, 'alpha_dash');
            }

            // Rule unique
            if (str_contains($fieldValue['rules'], 'unique')) {

                $arr = explode(',', $fieldValue['rules']);

                $uniqueRule = array_filter($arr, function ($value) {
                    return str_contains($value, 'unique');
                });

                $table = explode(':', array_values($uniqueRule)[0])[1];

                /**
                 * Se la regola non presenta flag (rappresentata da il trattino), cerco nel DB se il valore è già presente.
                 * Se lo è ritorno errore
                 **/
                if (!str_contains($table, '-')) {
                    $fieldInDb = Application::$app->builder
                        ->select($table)
                        ->where($field, $fieldValue[$field])
                        ->first();

                    if ($fieldInDb) {
                        $this->addError($field, 'unique', ['table' => $table]);
                    }
                } else {
                    /**
                     * Se viene passato anche l'ID del model, cerco tra tutti i record (diversi dal model passato come argomento)
                     * se esiste un valore come quello passato
                     */
                    $modelId = trim(substr($table, strpos($table, '-')), '-');

                    $table = substr($table, 0, strpos($table, '-'));

                    $models = Application::$app->builder
                        ->select($table)
                        ->where('id', $modelId, '!=')
                        ->get();

                    $columns = array_column($models, $field);

                    if (in_array($fieldValue[$field], $columns)) {
                        $this->addError($field, 'unique', ['table' => $table]);
                    }
                }
            }

            // Rule exists
            if (str_contains($fieldValue['rules'], 'exists')) {

                $arr = explode(',', $fieldValue['rules']);

                $existsRule = array_filter($arr, function ($value) {
                    return str_contains($value, 'exists');
                });

                $table = explode(':', array_values($existsRule)[0])[1];

                $fieldInDb = Application::$app->builder
                    ->select($table)
                    ->where($field, $fieldValue[$field])
                    ->first();

                if (!$fieldInDb) {
                    $this->addError($field, 'exists', ['table' => $table]);
                }
            }

            // Rule min
            if (str_contains($fieldValue['rules'], 'min')) {

                $arr = explode(',', $fieldValue['rules']);

                $minRule = array_filter($arr, function ($value) {
                    return str_contains($value, 'min');
                });

                // Reindex con array_values in quanto l'index di $minRule varia in base alla posizione in cui si trova nell'array rules
                $min = explode(':', array_values($minRule)[0]);

                if (strlen($fieldValue[$field]) < $min[1]) {
                    $this->addError($field, 'min', ['min' => $min[1]]);
                }
            }

            // Rule max
            if (str_contains($fieldValue['rules'], 'max')) {

                $arr = explode(',', $fieldValue['rules']);

                $maxRule = array_filter($arr, function ($value) {
                    return str_contains($value, 'max');
                });

                $max = explode(':', array_values($maxRule)[0]);

                if (strlen($fieldValue[$field]) > $max[1]) {
                    $this->addError($field, 'max', ['max' => $max[1]]);
                }
            }

            // Rule match
            if (str_contains($fieldValue['rules'], 'match')) {

                $arr = explode(',', $fieldValue['rules']);

                $matchRule = array_filter($arr, function ($value) {
                    return str_contains($value, 'match');
                });

                $match = explode(':', array_values($matchRule)[0]);

                // Se il valore del campo con il match, è diverso dal campo che deve matchare creo errore
                if ($fieldValue[$field] !== $data[$match[1]]) {
                    $this->addError($field, 'match', ['match' => $match[1]]);
                }
            }

            // Rule letter
            if (str_contains($fieldValue['rules'], 'letter') && !preg_match('/[a-z]+/', $fieldValue[$field])) {
                $this->addError($field, 'letter');
            }

            // Rule number
            if (str_contains($fieldValue['rules'], 'number') && !preg_match('/[\d]+/', $fieldValue[$field])) {
                $this->addError($field, 'number');
            }

            // Rule upper
            if (str_contains($fieldValue['rules'], 'upper') && !preg_match('/[A-Z]+/', $fieldValue[$field])) {
                $this->addError($field, 'upper');
            }

            // Rule special_char
            if (str_contains($fieldValue['rules'], 'special_char') && !preg_match('/[!#$%&?@_]/', $fieldValue[$field])) {
                $this->addError($field, 'special_char');
            }

            // Rule required -> deve rimanere ultima nei controlli, così da avere la priorità nelle viste
            if (str_contains($fieldValue['rules'], 'required') && !$fieldValue[$field]) {
                $this->addError($field, 'required');
            }
        }
    }

    /** 
     * @param array $validationRules
     * @return void
     * @throws NotFoundException
     */
    protected function ruleExists(array $rules): void
    {
        /** 
         *  Prendo i valori dell'array, es: ['required', 'alpha_dash] 
         *  Flat l'array con array_merge
         *  E prendo solo i valori unici
         */
        $rules = array_unique(array_merge(...array_values($rules)));

        foreach ($rules as $rule) {

            // Se è una regola "composta", prendo tutti i caratteri prima del : 
            if (strpos($rule, ':')) {
                $rule = substr($rule, 0, strpos($rule, ':'));
            }

            if (!in_array($rule, array_keys($this->availableRules))) {
                throw new NotFoundException("Validation rule $rule doesn't exist");
            }
        }
    }

    /**
     * @param string $field
     * @param string $rule
     * @param array  $params
     * @return void
     */
    protected function addError(string $field, string $rule, array $params = []): void
    {
        $message = $this->availableRules[$rule];

        $message = str_replace(':field:', $field, $message);

        // Se ci sono parametri, li sostituisco al placeholder all'interno dei messaggi di errore relativi
        if ($params) {
            if (str_contains($message, "{" . array_keys($params)[0] . "}")) {
                $message = str_replace("{" . array_keys($params)[0] . "}", array_values($params)[0], $message);
            }
        }

        $this->errors[$field] = $message;
    }
}
