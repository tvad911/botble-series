<?php

namespace Botble\Series\Forms;

use Botble\Base\Forms\FormAbstract;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Series\Http\Requests\SeriesRequest;
use Botble\Series\Models\Series;

class SeriesForm extends FormAbstract
{

    /**
     * {@inheritDoc}
     */
    public function buildForm()
    {
        $this
            ->setupModel(new Series)
            ->setValidatorClass(SeriesRequest::class)
            ->withCustomFields()
            ->add('name', 'text', [
                'label' => trans('core/base::forms.name'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder' => trans('core/base::forms.name_placeholder'),
                    'data-counter' => 120,
                ],
            ])
            ->add('description', 'textarea', [
                'label' => trans('core/base::forms.description'),
                'label_attr' => ['class' => 'control-label'],
                'attr' => [
                    'rows' => 4,
                    'placeholder' => trans('core/base::forms.description_placeholder'),
                    'data-counter' => 400,
                ],
            ])
            ->add('status', 'customSelect', [
                'label' => trans('core/base::tables.status'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'class' => 'form-control select-full',
                ],
                'choices' => BaseStatusEnum::labels(),
            ])
            ->setBreakFieldPoint('status');
    }
}
