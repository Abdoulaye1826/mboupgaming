<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Facture {{ $invoice->invoice_number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #212529;
            margin: 0;
            padding: 24px;
            background: #f8f9fa;
        }
        .invoice-print {
            max-width: 900px;
            margin: 0 auto;
            padding: 24px;
            background: #fff;
            border: 1px solid #dee2e6;
        }
        .invoice-print h2 {
            margin-bottom: 0.5rem;
        }
        .invoice-print .section {
            margin-bottom: 24px;
        }
        .invoice-print .header,
        .invoice-print .footer {
            margin-bottom: 24px;
        }
        .invoice-print .table {
            width: 100%;
            border-collapse: collapse;
        }
        .invoice-print .table th,
        .invoice-print .table td {
            padding: 12px;
            border: 1px solid #dee2e6;
        }
        .invoice-print .text-end {
            text-align: right;
        }
        .invoice-print .text-right {
            text-align: right;
        }
        .no-print {
            display: none;
        }
        @media print {
            body {
                background: #fff;
                padding: 0;
            }
            .invoice-print {
                border: none;
                box-shadow: none;
                margin: 0;
                padding: 0;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
<div class="invoice-print">
    <div class="section header">
        <div style="display:flex; justify-content:space-between; align-items:flex-start; gap:16px; flex-wrap:wrap;">
            <div>
                <h2>Facture</h2>
                <p style="margin:0;"><strong>Numéro :</strong> {{ $invoice->invoice_number }}</p>
                <p style="margin:0;"><strong>Date :</strong> {{ $invoice->issued_at->format('d/m/Y') }}</p>
                <p style="margin:0;"><strong>Statut :</strong> {{ $invoice->status->label() }}</p>
            </div>
            <div style="text-align:right;">
                <p style="margin:0;"><strong>Client :</strong></p>
                <p style="margin:0;">{{ $invoice->customer?->full_name ?? 'Client anonyme' }}</p>
                <p style="margin:0;">{{ $invoice->customer?->phone ?? '—' }}</p>
                <p style="margin:0;">{{ $invoice->customer?->email ?? '—' }}</p>
            </div>
        </div>
    </div>

    <div class="section">
        <h4 style="margin-bottom:8px;">Vente associée</h4>
        <p style="margin:0 0 4px 0;"><strong>Référence vente :</strong> {{ $invoice->sale?->sale_number ?? '—' }}</p>
        <p style="margin:0;"><strong>Date vente :</strong> {{ $invoice->sale?->sale_date?->format('d/m/Y') ?? '—' }}</p>
    </div>

    <div class="section">
        <table class="table">
            <thead>
                <tr>
                    <th>Description</th>
                    <th class="text-end">Montant</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Sous-total HT</td>
                    <td class="text-right">{{ number_format($invoice->subtotal_ht, 2, ',', ' ') }} FCFA</td>
                </tr>
                <tr>
                    <td>Taxe</td>
                    <td class="text-right">{{ number_format($invoice->tax_amount, 2, ',', ' ') }} FCFA</td>
                </tr>
                <tr>
                    <th>Total TTC</th>
                    <th class="text-right">{{ number_format($invoice->total_ttc, 2, ',', ' ') }} FCFA</th>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="section footer" style="display:flex; justify-content:space-between; align-items:center; gap:16px; flex-wrap:wrap;">
        <div>
            <p style="margin:0;"><strong>Remarques :</strong></p>
            <p style="margin:0;">Merci pour votre confiance.</p>
        </div>
        <button class="no-print" style="padding:10px 18px; background:#0d6efd; color:#fff; border:none; border-radius:4px; cursor:pointer;" onclick="window.print()">Imprimer</button>
    </div>
</div>
</body>
</html>
