<?php

/**
 * OUTER Group that applies CSRF to routes
 *
 * @var $app
 * @var $container
 *
 */
$app->group('', function () use($app, $container) {

    /**
     * adding to view : LOCALIZATION
     */
    $app->get('/translate/{lang}', ['app\controllers\TranslationController', 'switch'])->setName('translate.switch');

});