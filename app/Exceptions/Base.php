<?php

namespace App\Exceptions;

class Base extends \Exception {

    protected int $http_error_code = 500;
    protected ?string $description = null;

    /**
     * @param int|null $http_error_code
     */
    public function setHttpErrorCode(?int $http_error_code): void {
        $this->http_error_code = $http_error_code;
    }

    /**
     * @return int|null
     */
    public function getHttpErrorCode(): ?int {
        return $this->http_error_code;
    }

    /**
     * @param string|null $description
     */
    public function setDescription(?string $description): void {
        $this->description = $description;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string {
        return $this->description;
    }
}
