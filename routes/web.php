<?php

foreach (['public', 'auth', 'youth', 'manager', 'verifier', 'admin'] as $workspace) {
    require __DIR__.'/'.$workspace.'.php';
}
