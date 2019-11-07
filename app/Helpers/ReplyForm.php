<?php


namespace App\Helpers;

use Kris\LaravelFormBuilder\Form;
use Kris\LaravelFormBuilder\Field;


class ReplyForm extends Form
{
    public function buildForm()
    {
        $this
            ->add('message', 'textarea', [
                     'rules' => 'required|max:1000',
                    'label' => 'Сообщение'
                ]);

        $this
            ->add('submit', 'submit', [
                'label' => 'Сохранить'
            ]);
    }
}
