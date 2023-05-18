<?php 
    function sanitize_date($date) {
        $date_parts = explode('-', $date);
        
        if (count($date_parts) !== 3) {
            return false;
        }
        
        $day = intval($date_parts[0]);
        $month = intval($date_parts[1]);
        $year = intval($date_parts[2]);
        
        if ($day < 1 || $day > 31 || $month < 1 || $month > 12 || $year < 1900) {
            return false;
        }
        
        $datetime = DateTime::createFromFormat('d/m/Y', $date);
        $errors = DateTime::getLastErrors();
        
        if ($errors['warning_count'] > 0 || $errors['error_count'] > 0) {
            return false;
        }
        return $datetime;
    }

    function sanitize_number_positive($value) {
        $sanitized_value = max(0, intval($value));
        return $sanitized_value;
    }

    function sanitize_percentage($value) {
        $sanitized_value = max(0, min(100, floatval($value)));
        return $sanitized_value;
    }

    function sanitize_gender($gender) {
        $allowed_genders = array('male', 'female', 'other');

        $gender = strtolower($gender);

        if (in_array($gender, $allowed_genders)) {
            return $gender;
        } else {
            return false;
        }
    }
?>