<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body>

<h2>Contract Status Update</h2>

<p>
A contract status has changed and requires attention.
</p>

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
        <td><strong>Customer</strong></td>
        <td>{{ $contract->customer_pic_name }}</td>
    </tr>

    <tr>
        <td><strong>Old Status</strong></td>
        <td>{{ strtoupper($oldStatus) }}</td>
    </tr>

    <tr>
        <td><strong>New Status</strong></td>
        <td>{{ strtoupper($newStatus) }}</td>
    </tr>

    <tr>
        <td><strong>End Date</strong></td>
        <td>{{ $contract->end_date->format('d M Y') }}</td>
    </tr>
</table>

<br>

<p>
This notification was generated automatically by VasTrack.
</p>

</body>
</html>