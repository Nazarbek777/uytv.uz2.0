@extends('layouts.page')

@section('content')
    @php
        $statusBadges = [
            'published' => ['label' => __('Nashr qilingan'), 'class' => 'success'],
            'pending' => ['label' => __('Tasdiqlashda'), 'class' => 'warning'],
            'draft' => ['label' => __('Qoralama'), 'class' => 'secondary'],
            'rejected' => ['label' => __('Rad etilgan'), 'class' => 'danger'],
            'needs_changes' => ['label' => __('Qayta ishlash kerak'), 'class' => 'danger'],
            'approved' => ['label' => __('Tasdiqlangan'), 'class' => 'success'],
        ];
    @endphp

    <div class="page-title">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12">
                    <h2 class="ipt-title">{{ __('Mening panelim') }}</h2>
                    <span class="ipn-subtitle">{{ __('E’lonlaringiz, tasdiqlash jarayoni va ko‘rsatkichlarni real vaqtda kuzating') }}</span>
                </div>
            </div>
        </div>
    </div>

    <section class="gray-simple">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-3 col-sm-6">
                    <div class="dashboard-stat bg-primary">
                        <div class="dashboard-stat-content">
                            <h4>{{ $stats['total'] }}</h4>
                            <span>{{ __('Jami e’lonlar') }}</span>
                        </div>
                        <div class="dashboard-stat-icon"><i class="bi bi-card-list"></i></div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="dashboard-stat bg-success">
                        <div class="dashboard-stat-content">
                            <h4>{{ $stats['published'] }}</h4>
                            <span>{{ __('Faol e’lonlar') }}</span>
                        </div>
                        <div class="dashboard-stat-icon"><i class="bi bi-check2-circle"></i></div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="dashboard-stat bg-warning">
                        <div class="dashboard-stat-content">
                            <h4>{{ $stats['pending'] }}</h4>
                            <span>{{ __('Tasdiqlashda') }}</span>
                        </div>
                        <div class="dashboard-stat-icon"><i class="bi bi-hourglass-split"></i></div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="dashboard-stat bg-danger">
                        <div class="dashboard-stat-content">
                            <h4>{{ $stats['rejected'] }}</h4>
                            <span>{{ __('Rad etilgan') }}</span>
                        </div>
                        <div class="dashboard-stat-icon"><i class="bi bi-x-circle"></i></div>
                    </div>
                </div>
            </div>

            <div class="row g-4 mt-1">
                <div class="col-md-4 col-sm-6">
                    <div class="dashboard-stat bg-info">
                        <div class="dashboard-stat-content">
                            <h4>{{ number_format($stats['views']) }}</h4>
                            <span>{{ __('Umumiy ko‘rishlar') }}</span>
                        </div>
                        <div class="dashboard-stat-icon"><i class="bi bi-eye"></i></div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6">
                    <div class="dashboard-stat bg-secondary">
                        <div class="dashboard-stat-content">
                            <h4>{{ number_format($stats['favorites']) }}</h4>
                            <span>{{ __('Saralanganlarga qo‘shilishlar') }}</span>
                        </div>
                        <div class="dashboard-stat-icon"><i class="bi bi-heart"></i></div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-12">
                    <div class="dashboard-stat bg-dark">
                        <div class="dashboard-stat-content">
                            <h4>{{ $stats['draft'] }}</h4>
                            <span>{{ __('Qoralamalar') }}</span>
                        </div>
                        <div class="dashboard-stat-icon"><i class="bi bi-pencil-square"></i></div>
                    </div>
                </div>
            </div>

            <div class="row g-4 mt-2">
                <div class="col-xl-8">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-0">{{ __('Oxirgi 6 oyda joylangan e’lonlar') }}</h5>
                                <small class="text-muted">{{ __('Yangi e’lonlar soni bo‘yicha') }}</small>
                            </div>
                            <span class="badge bg-light text-dark">{{ __('Real-time') }}</span>
                        </div>
                        <div class="card-body">
                            <canvas id="listingPerformanceChart" height="120"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white border-0">
                            <h5 class="mb-0">{{ __('Tasdiqlash navbati') }}</h5>
                            <small class="text-muted">{{ __('Moderator javobi kutilmoqda yoki izoh qoldirilgan') }}</small>
                        </div>
                        <div class="card-body">
                            @forelse($approvalQueue as $property)
                                @php
                                    $meta = $statusBadges[$property->status] ?? ['label' => ucfirst($property->status), 'class' => 'secondary'];
                                    $lastNote = $property->approval_notes ?? optional(collect($property->approval_history)->last())['meta']['reason'] ?? null;
                                @endphp
                                <div class="border-bottom pb-3 mb-3">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="mb-1">{{ $property->title }}</h6>
                                            <small class="text-muted">{{ $property->updated_at->format('d.m.Y H:i') }}</small>
                                        </div>
                                        <span class="badge bg-{{ $meta['class'] }}">{{ $meta['label'] }}</span>
                                    </div>
                                    @if($lastNote)
                                        <p class="text-muted small mb-0 mt-2">
                                            <i class="bi bi-chat-left-text me-1"></i>{{ $lastNote }}
                                        </p>
                                    @endif
                                    <div class="mt-2 d-flex gap-2">
                                        <a href="{{ route('provider.properties.edit', $property->id) }}" class="btn btn-sm btn-light">
                                            <i class="bi bi-pencil-square me-1"></i>{{ __('Tahrirlash') }}
                                        </a>
                                        @if($property->status === 'rejected')
                                            <form method="POST" action="{{ route('provider.properties.submit', $property->id) }}">
                                                @csrf
                                                <button class="btn btn-sm btn-outline-primary" type="submit">
                                                    <i class="bi bi-send-check me-1"></i>{{ __('Qayta yuborish') }}
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <p class="text-muted mb-0">{{ __('Navbatdagi tasdiqlash uchun e’lonlar yo‘q.') }}</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4 mt-2">
                <div class="col-xl-7">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-0">
                            <h5 class="mb-0">{{ __('So‘nggi yangilangan e’lonlar') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table align-middle">
                                    <thead>
                                        <tr>
                                            <th>{{ __('Sarlavha') }}</th>
                                            <th>{{ __('Holat') }}</th>
                                            <th>{{ __('Ko‘rishlar') }}</th>
                                            <th>{{ __('Yangilangan') }}</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($recentProperties as $property)
                                            @php
                                                $meta = $statusBadges[$property->status] ?? ['label' => ucfirst($property->status), 'class' => 'secondary'];
                                            @endphp
                                            <tr>
                                                <td>
                                                    <div class="fw-semibold">{{ $property->title }}</div>
                                                    <small class="text-muted">{{ ucfirst($property->property_type) ?? __('Tasniflanmagan') }}</small>
                                                </td>
                                                <td><span class="badge bg-{{ $meta['class'] }}">{{ $meta['label'] }}</span></td>
                                                <td>{{ number_format($property->views) }}</td>
                                                <td>{{ $property->updated_at->format('d.m.Y H:i') }}</td>
                                                <td class="text-end">
                                                    <a href="{{ route('provider.properties.edit', $property->id) }}" class="btn btn-sm btn-outline-secondary">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center text-muted py-4">
                                                    {{ __('Hozircha e’lonlar mavjud emas.') }}
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-5">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white border-0">
                            <h5 class="mb-0">{{ __('Eng ko‘p ko‘rilgan e’lonlar') }}</h5>
                            <small class="text-muted">{{ __('Top traffic') }}</small>
                        </div>
                        <div class="card-body">
                            @forelse($topViewedProperties as $property)
                                <div class="d-flex align-items-center justify-content-between border-bottom pb-3 mb-3">
                                    <div>
                                        <h6 class="mb-1">{{ $property->title }}</h6>
                                        <small class="text-muted">{{ __('Ko‘rishlar: :views', ['views' => number_format($property->views)]) }}</small>
                                    </div>
                                    <a href="{{ route('property.show', $property->slug) }}" target="_blank" class="btn btn-sm btn-light">
                                        <i class="bi bi-box-arrow-up-right"></i>
                                    </a>
                                </div>
                            @empty
                                <p class="text-muted mb-0">{{ __('Hali statistikalar uchun ma’lumotlar yetarli emas.') }}</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4 mt-2">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">{{ __('Tasdiqlash faoliyati') }}</h5>
                            <small class="text-muted">{{ __('Oxirgi 8 ta jarayon') }}</small>
                        </div>
                        <div class="card-body">
                            @if($activityTimeline->isEmpty())
                                <p class="text-muted mb-0">{{ __('Hozircha tasdiqlash tarixi mavjud emas.') }}</p>
                            @else
                                <ul class="list-unstyled mb-0">
                                    @foreach($activityTimeline as $event)
                                        @php
                                            $meta = $statusBadges[$event['status']] ?? ['label' => ucfirst($event['status']), 'class' => 'secondary'];
                                            $reason = $event['meta']['reason'] ?? null;
                                            $actor = $event['meta']['reviewer'] ?? ($event['meta']['actor'] ?? null);
                                        @endphp
                                        <li class="border-bottom pb-3 mb-3">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <span class="badge bg-{{ $meta['class'] }}">{{ $meta['label'] }}</span>
                                                    <strong class="ms-2">{{ $event['title'] }}</strong>
                                                </div>
                                                <small class="text-muted">{{ $event['timestamp']->format('d.m.Y H:i') }}</small>
                                            </div>
                                            <div class="text-muted small mt-2">
                                                @if($reason)
                                                    <i class="bi bi-chat-left-text me-1"></i>{{ $reason }}
                                                @endif
                                                @if($actor)
                                                    <span class="ms-2"><i class="bi bi-person-circle me-1"></i>{{ $actor }}</span>
                                                @endif
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('listingPerformanceChart');
            if (!ctx) {
                return;
            }

            const chart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: @json($chartLabels),
                    datasets: [{
                        label: '{{ __('Joylangan e’lonlar') }}',
                        data: @json($chartValues),
                        borderColor: '#2d55a4',
                        borderWidth: 3,
                        backgroundColor: 'rgba(45, 85, 164, 0.15)',
                        tension: 0.3,
                        fill: true,
                        pointRadius: 4,
                        pointBackgroundColor: '#fff',
                        pointBorderColor: '#2d55a4'
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            }
                        }
                    }
                }
            });
        });
    </script>
@endpush

