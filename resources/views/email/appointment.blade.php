Dear customer {{ $emailData['name'] }},<br>
<p>Thank you for booking your appointment with {{ $emailData['business_name'] }}.</p>

<p>The details of your appointment are below:</p>
Time & Date: {{ $emailData['time'] }}, {{ $emailData['date'] }}<br></br>
With: {{ $emailData['staff'] }}<br></br>
Services: {{ $emailData['services'] }}<br></br>

<p>Location: {{ $emailData['business_address'] }}</p>
Contact us: {{ $emailData['business_phone'] }}<br></br>

<p>Sincerely,</p>
<p>Minh Nguyen</p>