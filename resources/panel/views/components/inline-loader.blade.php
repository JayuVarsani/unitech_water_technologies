@props(['target' => null, 'text' => 'primary'])
<span class="translate-middle-y me-3" wire:loading.inline
    @if ($target) wire:target="{{ $target }}" @endif>
    <span class="spinner-border spinner-border-sm text-{{ $text }}"></span>
</span>