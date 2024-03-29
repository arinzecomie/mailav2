<?php
class InputValidator
{
    protected $errors = [];
    protected $sanitizedData = [];

    public function processInput($input)
    {
        $input = trim($input);
        $input = stripslashes($input);
        $input = htmlspecialchars($input);
        return $input;
    }

    public function validate(array $fields, $source = 'post')
    {
        $data = ($source === 'get') ? $_GET : $_POST;

        foreach ($fields as $field) {
            if (isset($data[$field])) {
                $this->validateRequired($data[$field], ucfirst($field));
            } else {
                $this->addError("$field is missing.");
            }
        }
    }

    public function validateRequired($input, $fieldName)
    {
        $trimmedInput = $this->processInput($input);
        if (empty($trimmedInput)) {
            $this->addError("$fieldName is required.");
        } else {
            $this->addSanitizedData($fieldName, $trimmedInput);
        }
    }

    public function validateEmail($email, $fieldName)
    {
        $trimmedEmail = $this->processInput($email);
        if (!filter_var($trimmedEmail, FILTER_VALIDATE_EMAIL)) {
            $this->addError("Invalid $fieldName format.");
        } else {
            $this->addSanitizedData($fieldName, $trimmedEmail);
        }
    }

    public function validateMinLength($input, $fieldName, $minLength)
    {
        $trimmedInput = $this->processInput($input);
        if (strlen($trimmedInput) < $minLength) {
            $this->addError("$fieldName must be at least $minLength characters long.");
        } else {
            $this->addSanitizedData($fieldName, $trimmedInput);
        }
    }

    public function validateMatch($input1, $input2, $field1, $field2)
    {
        $trimmedInput1 = $this->processInput($input1);
        $trimmedInput2 = $this->processInput($input2);

        if ($trimmedInput1 !== $trimmedInput2) {
            $this->addError("$field1 and $field2 must match.");
        }
    }

    public function all()
    {
        return $this->sanitizedData;
    }

    public function pass()
    {
        return empty($this->errors);
    }

    public function get($fieldName)
    {
        return $this->sanitizedData[$fieldName] ?? null;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    protected function addError($message)
    {
        $this->errors[] = $message;
    }

    protected function addSanitizedData($fieldName, $value)
    {
        $this->sanitizedData[$fieldName] = $value;
    }
}
?>