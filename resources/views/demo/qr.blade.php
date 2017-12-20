@inject('parkingService', 'App\Services\ParkingService')
<!doctype html>
<html>
<body>
<style>
html,body{margin:0}
body{background: #fcebcc}
</style>
<div align="center" style="margin:10px">
    {{ $parking->group->name.' - '.$parking->label }}<br/>
    {{ ucfirst($parkingService::VEHICLES[ $vehicleType ]) }} Wheeler<br/>
    {{ $type }} QR Code<br/><br/>
    <img src="{{ asset($image) }}" />
</center>
</body>
</html>