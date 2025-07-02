@extends('layouts.member.app')

@section('title', 'Daftar Topik dan Panduan')

@section('content')
<section class="resume-section" id="guide">
    <div class="resume-section-content">
        <h2 class="mb-4">Daftar Topik dan Panduan Materi {{ $lesson->name }}</h2>
        <div class="row">
            <!-- Sidebar Topik -->
            <div class="col-md-4 mb-4">
                <div class="list-group" id="topicList">
                    @forelse ($topics ?? [] as $index => $topic)
                        <a href="#" 
                           class="list-group-item list-group-item-action"
                           data-topic-id="{{ $topic->id }}" 
                           data-name="{{ $topic->name }}"
                           data-steps="{{ $topic->steps }}">
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
                    <div class="alert alert-info">Pilih topik untuk melihat panduan, atau belum ada panduan tersedia.</div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const topicLinks = Array.from(document.querySelectorAll('#topicList a'));
        const guideContent = document.getElementById('guideContent');

        topicLinks.forEach(link => {
            link.addEventListener('click', function (e) {
                e.preventDefault();

                topicLinks.forEach(l => l.classList.remove('active'));
                this.classList.add('active');

                const topicName = this.getAttribute('data-name');
                const stepsHtml = this.getAttribute('data-steps') || '';
                const currentIndex = topicLinks.indexOf(this);

                if (!stepsHtml.trim()) {
                    guideContent.innerHTML = `
                        <div class="alert alert-info">
                            Belum ada panduan untuk topik ini.
                        </div>`;
                    return;
                }

                const decoded = decodeHtml(stepsHtml);
                const parsed = parseCodeBlocks(decoded);

                guideContent.innerHTML = `
                    <div class="card">
                        <div class="card-body">
                            <h4 class="mb-3">${topicName}</h4>
                            <div>${parsed}</div>
                            <div class="d-flex justify-content-between mt-4">
                                <button id="prevTopic" class="btn btn-secondary">&laquo; Sebelumnya</button>
                                <button id="nextTopic" class="btn btn-secondary">Berikutnya &raquo;</button>
                            </div>
                        </div>
                    </div>`;

                const btnPrev = document.getElementById('prevTopic');
                const btnNext = document.getElementById('nextTopic');

                btnPrev.style.visibility = currentIndex > 0 ? 'visible' : 'hidden';
                btnNext.style.visibility = currentIndex < topicLinks.length - 1 ? 'visible' : 'hidden';

                btnPrev?.addEventListener('click', () => topicLinks[currentIndex - 1]?.click());
                btnNext?.addEventListener('click', () => topicLinks[currentIndex + 1]?.click());
            });
        });

        function decodeHtml(html) {
            const txt = document.createElement('textarea');
            txt.innerHTML = html;
            return txt.value;
        }

        function escapeHtml(text) {
            return text.replace(/[&<>"']/g, function (char) {
                const escapeMap = {
                    '&': '&amp;',
                    '<': '&lt;',
                    '>': '&gt;',
                    '"': '&quot;',
                    "'": '&#039;',
                };
                return escapeMap[char];
            });
        }

        function parseCodeBlocks(html) {
            return html
                .replace(/<br\s*\/?>/gi, '\n') // replace <br> with newline
                .replace(/%%([\s\S]*?)%%/g, (_, code) => {
                    return `<pre><code>${escapeHtml(code.trim())}</code></pre>`;
                });
        }
    });
</script>
@endpush
