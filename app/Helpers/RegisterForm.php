<?php


namespace App\Helpers;

use Kris\LaravelFormBuilder\Form;
use Kris\LaravelFormBuilder\Field;


class RegisterForm extends Form
{
    public function buildForm()
    {

        $this
            ->add('name', 'text', array_merge([
                'rules' => 'required',
                'label' => 'Имя',


            ], $this->repeatedElements()));


        $this
            ->add('email', 'email', array_merge([
                'rules' => 'required',
                'label' => 'Email',


            ], $this->repeatedElements()));

        $this
            ->add('password', 'repeated', [
                'type'           => 'password',
                'second_name'    => 'password_confirmation',
                'label'          => 'Пароль',
                'first_options'  => array_merge([
                    'label' => 'Пароль',
                ], $this->repeatedElements()),
                'second_options' => array_merge([
                    'label' => 'Повторите пароль',
                ], $this->repeatedElements()),
            ]);

        $this
            ->add('birthday', 'date', array_merge([
                'rules' => 'required',
                'label' => 'Дата рождения',


            ], $this->repeatedElements()));

//        $this
//            ->add('girl', 'radio', array_merge([
//                'rules' => 'required',
//                'label' => 'Пол',
//                'value' => 1,
//                'checked' => false
//
//
//            ], $this->repeatedElements()));

        $this->add('girl', 'choice', [
            'wrapper'        => ['class' => 'form-group row'],
            'attr'  => ['class' => 'form-control col-md-6'],
            'choices' => [0 => 'Мужской', 1 => 'Женский'],
            'choice_options' => [
                'wrapper' => ['class' => 'form-getMessageForm form-getMessageForm-inline'],
                'attr'  => ['class' => 'form-getMessageForm-input'],
                'label_attr'     => ['class' => 'form-getMessageForm-label'],
            ],
            'label'    => 'Пол',
            'label_attr'     => ['class' => 'col-md-4 col-form-label text-md-right'],
            'selected' => [],
            'expanded' => true,
            'multiple' => false
        ]);

        $this
            ->add('avatar', 'file', [
                'template' => 'components.file_field'    // resources/views/posts/textarea.blade.php
            ]);



        $this
            ->add('submit', 'submit', [
                'label' => 'Сохранить'
            ]);
    }

    private function repeatedElements(): array
    {
        return [
            'wrapper'        => ['class' => 'form-group row'],
            'attr'           => ['class' => 'form-control col-md-6'],
            'label_attr'     => ['class' => 'col-md-4 col-form-label text-md-right'],
            'label_show'     => true,
            'errors'         => ['class' => 'is-invalid'],
            'error_messages' => ['error_messages']

        ];
    }
}
