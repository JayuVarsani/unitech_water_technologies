<?php

declare(strict_types=1);

namespace App\Src\Company\Modules\Inquiry\Component;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Src\Company\Modules\Inquiry\Form\InquiryForm;

class BaseInquiryComponent extends Component
{
    use WithFileUploads;

    public InquiryForm $form;

    private array $numericFields = [
        'form.width',
        'form.height',
        'form.qty',
        'form.sq_ft',
        'form.rate',
        'form.pesting_charge',
        'form.fitting_charge',
        'form.job_total_amount',
        'form.inquiry_pesting_charge',
        'form.inquiry_fitting_charge',
        'form.inquiry_transportation',
        'form.discount',
    ];

    public function addJob()
    {
        $this->form->addJob();
    }

    public function editJob($index)
    {
        $this->form->editJob($index);
    }

    public function deleteJob($index)
    {
        $this->form->deleteJob($index);
    }

    public function updateJob()
    {
        $this->form->updateJob();
    }

    public function updateCustomerData($customer_id)
    {
        $this->form->updateCustomerData($customer_id);
    }

    public function updateProductData($product_id)
    {
        $this->form->updateProductData($product_id);
    }

    public function updated($propertyName, $value)
    {
        if (in_array($propertyName, $this->numericFields)) {
            $field = str_replace('form.', '', $propertyName);
            $this->form->{$field} = max(0, is_numeric($value) ? (float) $value : 0);
        }

        if (in_array($propertyName, [
            'form.width',
            'form.height',
            'form.qty'
        ])) {
            if (!isset($this->form->product_id)) {
                $this->form->addError('product_id', 'Please select product first');
            }
        }
    
        if (in_array($propertyName, [
            'form.width',
            'form.height',
            'form.qty',
            'form.rate',
            'form.customer_id',
            'form.product_id',
            'form.pesting_charge',
            'form.fitting_charge',
        ])) {
            $this->form->jobFormUpdated();
        }

        if (in_array($propertyName, [
            'form.job_total_amount',
            'form.inquiry_pesting_charge',
            'form.inquiry_fitting_charge',
            'form.inquiry_transportation',
            'form.discount',
        ])) {
            $this->form->calculateEstimate();
        }
    }
}
