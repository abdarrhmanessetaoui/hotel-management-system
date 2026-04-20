@extends('layouts.app')

@section('content')
    <div class="d-flex flex-column h-100">
        <div class="d-flex justify-content-between align-items-center mb-4 flex-shrink-0">
            <h3 class="mb-0">Gestion des Villes</h3>
            <a href="{{ route('superadmin.cities.create') }}" class="btn btn-primary px-4 py-2 text-uppercase fw-bold">
                + Ajouter une Ville
            </a>
        </div>

        @include('components.show-success')

        <div class="card border-0 shadow-sm flex-grow-1 d-flex flex-column">
            <div class="card-body p-0 flex-grow-1 d-flex flex-column">
                <div class="table-responsive flex-grow-1">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="thead-brand text-uppercase">
                            <tr>
                                <th class="ps-4 py-3">#</th>
                                <th class="py-3">Nom</th>
                                <th class="py-3">Hôtels</th>
                                <th class="py-3">Image</th>
                                <th class="text-end pe-4 py-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($cities as $city)
                                <tr>
                                    <th class="ps-4"><span>{{ $loop->iteration }}</span></th>
                                    <td class="fw-bold text-dark"><span>{{ $city->name }}</span></td>
                                    <td>
                                        <span class="badge border text-dark bg-light px-3 py-2">
                                            {{ $city->hotels_count ?? 0 }} Hôtels
                                        </span>
                                    </td>
                                    <td>
                                        @if(!empty($city->image))
                                            <img src="{{ Str::startsWith($city->image,'http') ? $city->image : asset($city->image) }}"
                                                 class="rounded bg-light border shadow-sm" 
                                                 style="width: 60px; height: 40px; object-fit: cover;" 
                                                 alt="{{ $city->name }}">
                                        @else
                                            <div class="rounded bg-light d-flex align-items-center justify-content-center border" style="width: 60px; height: 40px;">
                                                <i class="bi bi-image text-muted"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="text-end pe-4">
                                        <div class="d-flex justify-content-end gap-2">
                                            <a href="{{ route('superadmin.cities.edit', $city->id) }}" 
                                               class="btn btn-warning fw-bold text-dark border-0 px-3 py-2 text-uppercase" style="font-size: 0.8rem; letter-spacing: 0.5px;">
                                                Modifier
                                            </a>
                                            <form method="post" action="{{ route('superadmin.cities.destroy', $city->id) }}" onsubmit="return confirm('Supprimer cette ville ?')">
                                                @csrf
                                                @method('delete')
                                                <button type="submit" class="btn btn-danger fw-bold border-0 px-3 py-2 text-uppercase" style="font-size: 0.8rem; letter-spacing: 0.5px;">
                                                    Supprimer
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <p class="text-muted mb-0">Aucune ville trouvée.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

