<?php

namespace AstralPHP\data\avatar;

class AvatarConfig {
    public int $AvatarID;
    public string $AvatarName;

    public function __construct(array $data) {
        $this->AvatarID = $data['AvatarID'];
        $this->AvatarName = $data['AvatarVOTag'];
    }
}