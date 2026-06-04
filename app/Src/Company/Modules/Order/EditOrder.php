<?php

declare(strict_types=1);

namespace App\Src\Company\Modules\Order;

use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderJob;
use App\Models\Product;
use App\Src\Company\Modules\Order\Form\OrderForm;
use App\Utility\Enums\OrderStatusEnum;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Url;

class EditOrder extends Component
{
    use WithFileUploads;

    public Order $order;

    public OrderForm $form;

    public $canEdit;

    #[Url]
    public $orderJob;
    
    private array $numericFields = [
        'form.width',
        'form.height',
        'form.qty',
        'form.sq_ft',
        'form.rate',
        'form.pesting_charge',
        'form.fitting_charge',
        'form.job_total_amount',
        'form.order_pesting_charge',
        'form.order_fitting_charge',
        'form.order_transportation',
        'form.discount',
    ];

    protected string $moduleUniqueName = 'company.order';

    public function mount(Order $order): void
    {
        if ($order->status == OrderStatusEnum::Completed->value || $order->status == OrderStatusEnum::Cancelled->value) {
            abort(403);
        }
        $this->canEdit = $this->hasPermission(type: 'edit');

        $this->form->measurement_unit = 'inch';
        $this->form->cancellation_reason = $this->order->cancellation_reason;
        $this->order = $order;
        
        $this->setValues();
        $orderJob = OrderJob::find($this->orderJob);
        if ($orderJob) {
            $searchIndex = collect($this->form->selectedJobs)->search(fn($item) => $item['job_no'] === $orderJob->job_no);
            if ($searchIndex !== false) {
                $this->editJob($searchIndex);
            }
        }
    }

    public function hasPermission(string $type = 'view', bool $abort = true): bool
    {
        return $this->moduleUniqueName ? auth()->user()->hasPermission($this->moduleUniqueName, $type, $abort) : true;
    }

    public function setValues(): void
    {
        $this->form->date = $this->order->date;
        $this->form->customer_id = $this->order->customer_id;
        $this->form->order_status = $this->order->status;
        $this->form->description = $this->order->description;
        $this->form->order_pesting_charge = $this->order->pesting_charge;
        $this->form->order_fitting_charge = $this->order->fitting_charge;
        $this->form->order_transportation = $this->order->transportation_charge;
        $this->form->discount = $this->order->discount;
        $this->form->job_total_amount = $this->order->total_amount;
        $this->form->estimateCount = $this->order->estimated_amount;
        $this->form->rounded_amount = $this->order->rounded_amount;
        $this->form->measurement_unit = 'inch';
        $this->form->order_status = $this->order->status;
        $this->form->isEdit = true;
        $this->form->selectedJobs = $this->order->orderJobs->map(function ($job) {
            return [
                'job_no' => $job->job_no,
                'image' => $job->getFirstMediaUrl('order_job_image'),
                'product_id' => $job->product_id,
                'product_name' => $job->product_name,
                'measurement_unit' => $job->measurement_unit,
                'width' => $job->width,
                'height' => $job->height,
                'qty' => $job->qty,
                'sq_ft' => $job->sq_ft,
                'rate' => $job->rate,
                'amount' => $job->amount,
                'status' => $job->status,
                'pesting_charge' => $job->pesting_charge,
                'fitting_charge' => $job->fitting_charge,
                // 'transportation_charge' => $job->transportation_charge,
                'narration' => $job->narration,
            ];
        })->toArray();
    }


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
            'form.order_pesting_charge',
            'form.order_fitting_charge',
            'form.order_transportation',
            'form.discount',
        ])) {
            $this->form->calculateEstimate();
        }
    }
    public function save(): void
    {
        $this->form->saveOrder($this->order);
        flashAlert(__('app.panel.update', ['name' => __('company.order')]), 'success');
        if ($this->orderJob) {
            $this->redirectRoute('company.orderjob.index');
        } else {
            $this->redirectRoute('company.order.index');
        }
    }

    public function render(): View
    {
        $title = __('app.panel.edit_name', ['name' => __('company.order')]);

        $getCustomers = Customer::where('company_id', Auth::user()->company_id)->get();
        $getProducts = Product::where('company_id', Auth::user()->company_id)->get();

        if ($this->canEdit) {
            return view('company::Order.views.form', [
                'title' => $title,
                'customer' => $getCustomers,
                'product' => $getProducts,
                'isEdit' => $this->form->isEdit,
                'existingLogo' => '',
                'company_id' => Auth::user()->company_id,
                'selectedJobs' => collect($this->form->selectedJobs ?? []),
            ])->layout(
                'panel::layout.app',
                [
                    'breadcrumb' => [
                        [__('company.order'), route('company.order.index')],
                        [__('app.panel.edit'), route('company.order.edit', $this->order->id)],
                    ],
                    'title' => $title,
                ]
            );
        } else {
            abort(403);
        }
    }
}
