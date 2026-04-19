@extends('layouts.app')

@section('content')
    @include('components.show-success')

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header d-flex align-items-center justify-content-between bg-white border-bottom py-3">
            <h3 class="mb-0 fw-bold">Types de Chambres — {{ $hotel->name ?? 'Hôtel' }}</h3>
            <a href="{{ route('admin.roomtypes.create') }}" class="btn btn-success btn-sm fw-bold px-3">
                + NOUVEAU TYPE
            </a>
        </div>
        <div class="card-body p-0">
            <table class="table table-hover align-middle mb-0" style="font-size: 0.85rem;">
                <thead class="thead-brand">
                <tr>
                    <th class="ps-4">#</th>
                    <th class="text-nowrap">Nom du Type</th>
                    <th class="text-nowrap">Description</th>
                    <th class="text-nowrap text-center">Chambres</th>
                    <th class="text-end pe-4">Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse($roomTypes as $type)
                    <tr>
                        <th class="ps-4 text-muted">{{ $loop->iteration }}</th>
                        <td class="fw-bold text-nowrap py-3">{{ $type->name }}</td>
                        <td class="text-muted small" style="max-width: 400px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                            {{ $type->description ?? 'Aucune description' }}
                        </td>
                        <td class="text-center">
                            <span class="badge bg-light text-dark border shadow-sm px-3" style="font-size: 0.75rem;">
                                {{ $type->rooms_count ?? $type->rooms()->count() }}
                            </span>
                        </td>
                        <td class="text-end pe-4">
                            <div class="d-flex justify-content-end gap-2">
                                <a class="btn btn-primary btn-sm px-3 fw-bold text-white rounded"
                                   href="{{ route('admin.roomtypes.edit', $type->id) }}" style="font-size: 0.75rem;">
                                    MODIFIER
                                </a>
                                <form method="post"
                                      action="{{ route('admin.roomtypes.destroy', $type->id) }}"
                                      class="m-0 p-0 d-inline-block">
                                    @csrf
                                    @method('delete')
                                    <button type="submit" class="btn btn-danger btn-sm px-3 fw-bold text-white rounded"
                                            onclick="return confirm('Souhaitez-vous vraiment supprimer ce type de chambre ? Attention, cela affectera les chambres liées.')"
                                            style="font-size: 0.75rem;">
                                        SUPPRIMER
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">
                            <p class="text-primary fw-bold mb-0 ps-4 py-4">Aucun type de chambre défini pour cet hôtel.</p>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
