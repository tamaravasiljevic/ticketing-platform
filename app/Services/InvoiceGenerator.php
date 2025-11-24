<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Support\Facades\Storage;

class InvoiceGenerator
{
    public static function generateForOrder(Order $order): Invoice
    {
        // 1. Kreiraj invoice zapis
        $invoice = Invoice::create([
            'invoice_number' => self::generateInvoiceNumber(),
            'order_id' => $order->id,
            'user_id' => $order->user_id,
            'subtotal' => $order->subtotal,
            'tax_total' => $order->tax_total,
            'discount_total' => $order->discount_total,
            'total' => $order->total,
            'currency' => $order->currency,
            'billing_details' => $order->billing_details ?? null,
        ]);

        // 2. create invoice item
        foreach ($order->items as $item) {
            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'description' => $item->description ?? $item->ticketType->name,
                'quantity' => $item->quantity,
                'unit_price' => $item->unit_price,
                'total_price' => $item->total_price,
                'metadata' => ['order_item_id' => $item->id],
            ]);
        }

        // 3. generate PDF
        $pdfPath = self::generatePdf($invoice);
        $invoice->update(['file_path' => $pdfPath]);

        return $invoice;
    }

    private static function generateInvoiceNumber(): string
    {
        return 'INV-' . date('Y') . '-' . str_pad(Invoice::max('id') + 1, 6, '0', STR_PAD_LEFT);
    }

    private static function generatePdf(Invoice $invoice): string
    {
        // dompdf, snappy, laravel-dompdf etc. can be used
        $html = view('pdf.invoice', ['invoice' => $invoice])->render();
        $pdf = app('dompdf.wrapper')->loadHTML($html);

        $fileName = $invoice->invoice_number . '.pdf';
        Storage::put('invoices/' . $fileName, $pdf->output());

        return 'invoices/' . $fileName;
    }
}
