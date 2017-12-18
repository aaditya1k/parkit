<table class="data">
    <thead>
        <tr>
            <th>ID</th>
            <th>Label</th>
            <th>Created</th>
            <th>Updated</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
    @forelse ($parkingLevels as $parkingLevel)
        <tr>
            <td>{{ $parkingLevel->parkingLevelId() }}</td>
            <td>{{ $parkingLevel->label }}</td>
            <td>{{ $parkingLevel->createdAt() }}</td>
            <td>{{ $parkingLevel->updatedAt() }}</td>
            <td class="option">
                <a href="{{ route('admin:parking-level:view', $parkingLevel->id) }}">View</a>
                <a href="{{ route('admin:parking-level:edit', $parkingLevel->id) }}">Edit</a>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="5" class="nothing-found">Nothing Found</td>
        </tr>
    @endforelse
    </tbody>
</table>