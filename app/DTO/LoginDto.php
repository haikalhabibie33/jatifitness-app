<?php

namespace App\DTO;

final readonly class LoginDto
{
    public function __construct(
        public string $username,
        public string $password,
        public bool $remember,
    )
    {}

    public static function fromPost(array $post): self
    {
        return new self(
            username: $post['username'],
            password: $post['password'],
            remember: (bool) ($post['remember'] ?? false),
        );
    }

    public static function fromJson(array $json): self
    {
        return new self(
            username: $json['username'],
            password:$json['password'],
            remember: (bool) ($json['remember'] ?? false)
        );
    }
}