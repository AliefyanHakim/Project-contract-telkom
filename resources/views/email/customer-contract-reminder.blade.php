<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body>

<h2>Contract Expiration Reminder</h2>

<p>
Dear Customer,
</p>

@if($daysRemaining <= 7)

<p>
This is an urgent reminder that your contract will expire within 7 days.
</p>

@else

<p>
This is a reminder that your contract will expire within 30 days.
</p>

@endif

<table border="1" cellpadding="8" cellspacing="0">

    <tr>
        <td><strong>Contract Number</strong></td>
        <td>{{ $contract->contract_number }}</td>
    </tr>

    <tr>
        <td><strong>Contract Name</strong></td>
        <td>{{ $contract->contract_name }}</td>
    </tr>

    <tr>
        <td><strong>Expiration Date</strong></td>
        <td>{{ $contract->end_date->format('d M Y') }}</td>
    </tr>

    <tr>
        <td><strong>Account Manager</strong></td>
        <td>{{ $contract->owner?->name }}</td>
    </tr>

    <tr>
        <td><strong>Email</strong></td>
        <td>{{ $contract->owner?->email }}</td>
    </tr>

</table>

<br>

<p>
Please contact your Account Manager regarding contract renewal.
</p>

<p>
Thank you.
</p>

</body>
</html>