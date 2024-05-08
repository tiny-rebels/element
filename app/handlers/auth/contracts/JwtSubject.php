<?php

namespace app\handlers\auth\contracts;

interface JwtSubject {

    public function getJwtIdentifier();
}