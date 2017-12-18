@extends('admin.layouts.app')

@section('content')
    <h2 class="head">{{ $group->name }}</h2>

    <?php
        $parkings = $group->parkings;
    ?>
    @if ($parkings->count() > 0)
        @foreach ($group->parkings as $parking)
        <table class="data">
            <thead>
                <tr>
                    <th>{{ $parking->label }}</th>
                    <th class="option">
                        <a href="{{ route('admin:parking:view', $parking->id) }}">View</a>
                        <a href="{{ route('admin:parking:edit', $parking->id) }}">Edit</a>
                    </th>
                </tr>
            </thead>
            <tbody>
            <?php
                $parkingLevels = $parking->parkingLevels;
            ?>
            @if ($parkingLevels->count() > 0)
                @foreach ($parkingLevels as $parkingLevel)
                    <tr>
                        <td>{{ $parkingLevel->label }}</td>
                        <td class="option">
                            <a href="{{ route('admin:parking-level:view', $parkingLevel->id) }}">View</a>
                            <a href="{{ route('admin:parking-level:edit', $parkingLevel->id) }}">Edit</a>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr><td colspan="2" class="nothing-found">No Parking levels found</td></tr>
            @endif
            </tbody>
        </table>
        @endforeach
    @else
        <div class="nothing-found">No Parkings found</div>
    @endif
@stop