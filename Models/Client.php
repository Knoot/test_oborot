<?php

namespace Models;

class Client
{
    private $name;

    private $surname;

    private $phone;

    private $comment;

    private $createdAt;

    public function __construct(?string $name, ?string $surname, $phone, ?string $comment, string $createdAt = "")
    {
        $this->name = $name;
        $this->surname = $surname;
        $this->phone = $phone;
        $this->comment = $comment;
        $this->createdAt = $createdAt;
    }

    public function getName(): string
    {
        return $this->ucfirst($this->name ?? '');
    }

    public function getSurname(): string
    {
        return $this->ucfirst($this->surname ?? '');
    }

    private function ucfirst($string)
    {
        return mb_strtoupper(mb_substr($string, 0, 1)) . mb_substr($string, 1);
    }

    public function getPhone()
    {
        return $this->phone ?? '';
    }

    public function getComment(): string
    {
        return $this->comment ? preg_replace("/\n/", "<br/>", $this->comment) : '';
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt ?? '';
    }

    public function validate(): array
    {
        $errors = [];
        if (!$this->validateName()) {
            $errors[] = "\"Имя\" - значение обязательно для заполнения,  должно быть больше 2ух символов, может
            содержать буквы и тире, максимальная длина 150 символов";
        }

        if (!$this->validateSurname()) {
            $errors[] = "\"Фамилия\" - значение  обязательно для заполнения, должно быть больше 2ух символов, может
            содержать буквы и тире, максимальная длина 150 символов";
        }

        if (!$this->validatePhone()) {
            $errors[] = "\"Мобильный телефон\" - значение обязательно для заполнения, должно быть больше или равно 10
            символам, должно состоять только из цифр";
        }

        return $errors;
    }

    private function validateName(): bool
    {
        if (! preg_match('/^[A-zА-я-]{3,150}$/u', $this->name)) {
            return false;
        }

        return true;
    }

    private function validateSurname(): bool
    {
        if (! preg_match('/^[A-zА-я-]{3,150}$/u', $this->surname)) {
            return false;
        }

        return true;
    }

    private function validatePhone(): bool
    {
        if (! preg_match('/^[0-9]{10,}$/', $this->phone)) {
            return false;
        }

        return true;
    }
}
