<?php

/**
 * Laravel-admin - admin builder based on Laravel.
 * @author z-song <https://github.com/z-song>
 *
 * Bootstraper for Admin.
 *
 * Here you can remove builtin form field:
 * Encore\Admin\Form::forget(['map', 'editor']);
 *
 * Or extend custom form field:
 * Encore\Admin\Form::extend('php', PHPEditor::class);
 *
 * Or require js and css assets:
 * Admin::css('/packages/prettydocs/css/styles.css');
 * Admin::js('/packages/prettydocs/js/main.js');
 *
 */
//Encore\Admin\Form::forget(['map', 'editor']);

use App\Admin\Extensions\LinkageListbox;
use App\Admin\Extensions\TestCaseUploader;
use App\Admin\Extensions\uEditor;
use App\Admin\Extensions\WrittenEditor;
use Encore\Admin\Form;

Form::extend('editor', uEditor::class);
//Form::extend('test_case', TestCaseUploader::class);
Form::extend('linkagelistbox',LinkageListbox::class);
Form::extend('written',WrittenEditor::class);
