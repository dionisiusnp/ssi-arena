@extends('layouts.member.app')

@section('title', 'Daftar Topik dan Panduan')

@section('content')
<section class="resume-section" id="guide">
    <div class="resume-section-content">
        <h2 class="mb-4">Daftar Topik dan Panduan</h2>
        <div class="row">
            <!-- Sidebar Topik -->
            <div class="col-md-4 mb-4">
                <div class="list-group" id="topicList">
                    @forelse ($topics ?? [] as $index => $topic)
                        <a href="#" 
                           class="list-group-item list-group-item-action {{ $index === 0 ? 'active' : '' }}"
                           data-topic-id="{{ $topic->id }}" 
                           data-steps='@json($topic->steps ?? [])'>
                            {{ $topic->name }}
                        </a>
                    @empty
                        <div class="alert alert-warning">Belum ada topik tersedia.</div>
                    @endforelse
                </div>
            </div>

            <!-- Konten Panduan -->
            <div class="col-md-8">
                <div id="guideContent">
                    @if (!empty($topics) && $topics->first() && $topics->first()->steps->count())
                        @foreach ($topics->first()->steps as $step)
                            <div class="card mb-3">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $step->name }}</h5>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <p class="mb-0"><strong>Input:</strong></p>
                                        <button class="btn btn-sm btn-outline-secondary btn-copy" data-target="input-{{ $step->id }}">
                                            📋 Salin
                                        </button>
                                    </div>
                                    <pre id="input-{{ $step->id }}" class="bg-light p-2">{{ $step->content_input }}</pre>

                                    <p><strong>Output:</strong></p>
                                    <pre class="bg-light p-2">{{ $step->content_output }}</pre>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="alert alert-info">Pilih topik untuk melihat panduan, atau belum ada panduan tersedia.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const topicLinks = document.querySelectorAll('#topicList a');
        const guideContent = document.getElementById('guideContent');

        function attachCopyButtons() {
            document.querySelectorAll('.btn-copy').forEach(btn => {
                btn.addEventListener('click', () => {
                    const targetId = btn.getAttribute('data-target');
                    const text = document.getElementById(targetId).textContent;

                    navigator.clipboard.writeText(text).then(() => {
                        const original = btn.innerHTML;
                        btn.innerHTML = "✅ Disalin!";
                        setTimeout(() => {
                            btn.innerHTML = original;
                        }, 1500);
                    });
                });
            });
        }

        topicLinks.forEach(link => {
            link.addEventListener('click', function (e) {
                e.preventDefault();

                topicLinks.forEach(l => l.classList.remove('active'));
                this.classList.add('active');

                const stepsJson = this.getAttribute('data-steps');
                let steps = [];

                try {
                    steps = JSON.parse(stepsJson);
                } catch (err) {
                    console.error('JSON parse error:', err);
                }

                if (!steps.length) {
                    guideContent.innerHTML = `
                        <div class="alert alert-info">
                            Belum ada panduan untuk topik ini.
                        </div>`;
                    return;
                }

                let html = '';
                steps.forEach(step => {
                    html += `
                        <div class="card mb-3">
                            <div class="card-body">
                                <h5 class="card-title">${step.name}</h5>
                                <div class="d-flex justify-content-between align-items-center">
                                    <p class="mb-0"><strong>Input:</strong></p>
                                    <button class="btn btn-sm btn-outline-secondary btn-copy" data-target="input-${step.id}">
                                        📋 Salin
                                    </button>
                                </div>
                                <pre id="input-${step.id}" class="bg-light p-2">${step.content_input ?? ''}</pre>

                                <p><strong>Output:</strong></p>
                                <pre class="bg-light p-2">${step.content_output ?? ''}</pre>
                            </div>
                        </div>`;
                });

                guideContent.innerHTML = html;
                attachCopyButtons();
            });
        });

        // Attach copy buttons for first render
        attachCopyButtons();
    });
</script>
@endpush
