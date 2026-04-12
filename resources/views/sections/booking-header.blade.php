<div class="container-fluid booking pb-5 wow fadeIn" data-wow-delay="0.1s">
    <div class="container">
        <div class="bg-white shadow" style="padding: 35px;">
            <h3 class="mb-4">Vérifier la disponibilité</h3>
            <form class="row g-2" method="get" action="{{ route('home') }}">
                <div class="col-md-10">
                    <div class="row g-2">
                        <div class="col-md-3">
                            <div class="date" id="date1" data-target-input="nearest">
                                <input name="check_in" type="text" placeholder="Date d'arrivée" data-target="#date1"
                                       class="form-control datetimepicker-input"
                                       data-toggle="datetimepicker"
                                       value="{{ request('check_in', $fields['check_in'] ?? '') }}"/>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="date" id="date2" data-target-input="nearest">
                                <input name="check_out" type="text" placeholder="Date de départ" data-target="#date2"
                                       class="form-control datetimepicker-input"
                                       data-toggle="datetimepicker"
                                       value="{{ request('check_out', $fields['check_out'] ?? '') }}"/>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <select name="no_peron" class="form-select">
                                <option value="">Adultes</option>
                                @for ($i = 1; $i <= 10; $i++)
                                    <option @selected(request('no_peron', $fields['no_peron'] ?? '') == $i)
                                            value="{{ $i }}">{{ $i }} {{ $i > 1 ? 'Adultes' : 'Adulte' }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select name="no_children" class="form-select">
                                <option value="0">Enfants</option>
                                @for ($i = 1; $i <= 6; $i++)
                                    <option value="{{ $i }}" @selected(request('no_children', $fields['no_children'] ?? '0') == $i)>{{ $i }} {{ $i > 1 ? 'Enfants' : 'Enfant' }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100 h-100">
                        <i class="fa fa-search me-2"></i>Rechercher
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Check-in date picker
        const date1Input = document.querySelector('#date1 input');
        if (date1Input) {
            date1Input.addEventListener('click', function() {
                $(this).datetimepicker('show');
            });
        }

        // Initialize Check-out date picker
        const date2Input = document.querySelector('#date2 input');
        if (date2Input) {
            date2Input.addEventListener('click', function() {
                $(this).datetimepicker('show');
            });
        }

        // Set minimum date to today for check-in
        $('#date1').datetimepicker({
            format: 'YYYY-MM-DD',
            minDate: moment(),
            locale: 'fr',
            icons: {
                time: 'fa fa-clock',
                date: 'fa fa-calendar',
                up: 'fa fa-arrow-up',
                down: 'fa fa-arrow-down',
                previous: 'fa fa-chevron-left',
                next: 'fa fa-chevron-right',
                today: 'fa fa-calendar-check',
                clear: 'fa fa-trash',
                close: 'fa fa-times'
            }
        });

        // Set minimum date to check-in date for check-out
        $('#date2').datetimepicker({
            format: 'YYYY-MM-DD',
            minDate: moment(),
            locale: 'fr',
            icons: {
                time: 'fa fa-clock',
                date: 'fa fa-calendar',
                up: 'fa fa-arrow-up',
                down: 'fa fa-arrow-down',
                previous: 'fa fa-chevron-left',
                next: 'fa fa-chevron-right',
                today: 'fa fa-calendar-check',
                clear: 'fa fa-trash',
                close: 'fa fa-times'
            }
        });

        // Update check-out min date when check-in changes
        $('#date1').on('change.datetimepicker', function(e) {
            $('#date2').datetimepicker('minDate', e.date);
        });
    });
</script>
@endpush
