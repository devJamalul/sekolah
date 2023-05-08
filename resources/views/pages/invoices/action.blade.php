@extends('components.datatable-action')

@push('item')
  <a class="dropdown-item" data-url="{{ route('invoices.destroy', ['invoice' => $invoice->id]) }}"
    data-redirect="{{ $redirect_url }}" onclick="softDelete(this)">Hapus</a>
@endpush
