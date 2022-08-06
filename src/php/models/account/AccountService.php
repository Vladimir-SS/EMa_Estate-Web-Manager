<?php
include_once DIR_MODELS . "Model.php";

class AccountService
{
    public function __construct()
    {
    }


    /**
     * Generates salt
     *
     * @return string $salt
     */
    public function generate_salt(): string
    {
        return random_bytes(4);
    }

    /**
     * Adds salt and papper to the password
     *
     * @param  mixed $password
     * @param  mixed $salt
     * @return string $passwordSP
     */
    public function add_salt_and_pepper(string $password, string $salt): string
    {
        return $password . $salt . ACCOUNT_PEPPER;
    }

    /**
     * Generates password hash
     *
     * @param  mixed $passwordSP
     * @return string $password_hash
     */
    public function generate_hash(string $passwordSP): string
    {
        return password_hash($passwordSP, PASSWORD_DEFAULT);
    }

    /**
     * Checks if the introduced password is the same with the password_hash in saved in the database
     *
     * @param  mixed $password
     * @param  mixed $salt
     * @param  mixed $passwordSP
     * @return bool
     */
    public function password_check(string $password, string $salt, string $passwordSP): bool
    {
        return password_verify($this->add_salt_and_pepper($password, $salt), $passwordSP);;
    }
}
