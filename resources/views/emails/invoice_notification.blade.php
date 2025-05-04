<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>فاتورة جديدة - {{ $data['invoice_id'] }}</title>
    <style>
        @font-face {
            font-family: 'Tajawal';
            src: url('https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap');
        }

        body {
            font-family: 'Tajawal', Arial, sans-serif;
            line-height: 1.8;
            color: #2d3748;
            background-color: #f8fafc;
            margin: 0;
            padding: 0;
        }

        .email-container {
            max-width: 650px;
            margin: 30px auto;
            background: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
            border: 1px solid #e2e8f0;
        }

        .email-header {
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            color: white;
            padding: 30px 20px;
            text-align: center;
            border-bottom: 4px solid #3730a3;
        }

        .email-header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
            letter-spacing: -0.5px;
        }

        .email-body {
            padding: 35px;
        }

        .invoice-details {
            margin-bottom: 30px;
        }

        .detail-row {
            display: flex;
            margin-bottom: 18px;
            padding-bottom: 18px;
            border-bottom: 1px solid #edf2f7;
            align-items: center;
        }

        .detail-label {
            font-weight: 600;
            color: #4a5568;
            width: 40%;
            font-size: 16px;
        }

        .detail-value {
            width: 60%;
            font-size: 15px;
            color: #1a202c;
        }

        .status {
            display: inline-block;
            padding: 6px 16px;
            border-radius: 24px;
            font-weight: 600;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-paid {
            background: #f0fff4;
            color: #38a169;
            border: 1px solid #c6f6d5;
        }

        .status-pending {
            background: #fffaf0;
            color: #dd6b20;
            border: 1px solid #feebc8;
        }

        .status-cancelled {
            background: #fff5f5;
            color: #e53e3e;
            border: 1px solid #fed7d7;
        }

        .email-footer {
            background: #f7fafc;
            padding: 25px;
            text-align: center;
            font-size: 14px;
            color: #718096;
            border-top: 1px solid #e2e8f0;
        }

        .logo {
            max-width: 180px;
            margin-bottom: 20px;
            filter: brightness(0) invert(1);
        }

        .total-amount {
            background: linear-gradient(to right, #f9fafb, #f3f4f6);
            padding: 25px;
            border-radius: 10px;
            text-align: center;
            margin: 30px 0;
            border: 1px solid #e5e7eb;
        }

        .total-amount h3 {
            margin: 0 0 12px 0;
            color: #4f46e5;
            font-size: 18px;
            font-weight: 600;
        }

        .total-value {
            font-size: 32px;
            font-weight: 700;
            color: #1f2937;
            letter-spacing: -1px;
        }

        .btn {
            display: inline-block;
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            color: white;
            text-decoration: none;
            padding: 14px 28px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            margin: 20px 0;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(79, 70, 229, 0.2);
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(79, 70, 229, 0.3);
        }

        .divider {
            height: 1px;
            background: linear-gradient(to right, transparent, #e2e8f0, transparent);
            margin: 25px 0;
        }

        .company-info {
            margin-top: 15px;
            font-size: 13px;
            color: #64748b;
        }
    </style>
</head>

<body>
    <div class="email-container">
        <div class="email-header">
            <h1>فاتورة جديدة #{{ $data['invoice_id'] }}</h1>
        </div>

        <div class="email-body">
            <div class="invoice-details">
                <div class="detail-row">
                    <div class="detail-label">رقم الفاتورة:</div>
                    <div class="detail-value">{{ $data['invoice_id'] }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">تاريخ الفاتورة:</div>
                    <div class="detail-value">{{ $data['invoice_Date'] }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">تم إنشاؤها بواسطة:</div>
                    <div class="detail-value">{{ $data['user_send'] }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">القسم:</div>
                    <div class="detail-value">{{ $data['section_name'] }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">المنتج:</div>
                    <div class="detail-value">{{ $data['product_name'] }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">حالة الفاتورة:</div>
                    <div class="detail-value">
                        <span class="status status-{{ strtolower($data['Status']) }}">
                            {{ $data['Status'] }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="divider"></div>

            <div class="total-amount">
                <h3>المبلغ الإجمالي</h3>
                <div class="total-value">{{ $data['Total'] }} ج.م</div>
            </div>

            <div style="text-align: center;">
                <a href="{{ route('invoices.show', $invoices->id) }}" class="btn">عرض الفاتورة</a>
            </div>
        </div>

        <div class="email-footer">
            <p>لأي استفسارات، يرجى التواصل مع فريق الدعم لدينا على support@osoft.com</p>
            <div class="company-info">
                <p>شركة أو سوفت - الاسكندرية - جمهورة مصر العربية</p>
                <p>هاتف: +201024456408 | البريد الإلكتروني: info@osoft.com</p>
            </div>
            <p>© {{ date('Y') }} جميع الحقوق محفوظة لشركة OSOFT</p>
        </div>
    </div>
</body>

</html>
