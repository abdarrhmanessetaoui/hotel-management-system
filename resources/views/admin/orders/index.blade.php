@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">
            <h2>Réservations à Venir</h2>
        </div>
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th scope="col">Nom de la Chambre</th>
                    <th scope="col">Arrivée</th>
                    <th scope="col">Départ</th>
                    <th scope="col">Prix Total</th>
                    <th scope="col">Réservé le</th>
                </tr>
                </thead>
                <tbody>
                @forelse($orders as $order)
                    <tr>
                        <td>{{ $order->room->roomtype->name }}</td>
                        <td>{{ $order->check_in }}</td>
                        <td>{{ $order->check_out }}</td>
                        <td>${{ $order->room->price * $order->stayDays }}</td>
                        <td>{{ $order->created_at }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">
                            <p class="text-primary fw-bold mb-0">Vous n'avez aucune commande.</p>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
