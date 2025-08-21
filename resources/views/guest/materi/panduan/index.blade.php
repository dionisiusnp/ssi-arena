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
                           data-steps="{{ htmlentities($topic->steps) }}">
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

        topicLinks.forEach((link, index) => {
            link.addEventListener('click', function (e) {
                e.preventDefault();

                topicLinks.forEach(l => l.classList.remove('active'));
                this.classList.add('active');

                const topicName = this.dataset.name;
                const stepsHtml = decodeHtml(this.dataset.steps || '');
                const currentIndex = index;

                if (!stepsHtml.trim()) {
                    guideContent.innerHTML = `<div class="alert alert-info">Belum ada panduan untuk topik ini.</div>`;
                    return;
                }

                const matches = [...stepsHtml.matchAll(/%%codeblock:(\d+)%%/g)];

                if (matches.length > 0) {
                    const fetches = matches.map(match => {
                        const id = match[1];
                        const placeholder = match[0];

                        return fetch(`/code/detail/${id}`)
                            .then(res => res.json())
                            .then(data => ({
                                placeholder,
                                code: data.code || 'Gagal memuat konten.',
                                language: data.language || 'plaintext'
                            }))
                            .catch(() => ({
                                placeholder,
                                code: 'Gagal memuat konten.',
                                language: 'plaintext'
                            }));
                    });

                    Promise.all(fetches).then(results => {
                        let finalHtml = stepsHtml;
                        results.forEach(({ placeholder, code, language }, idx) => {
                            const codeBlock = `
                                <div class="position-relative mb-3">
                                    <button class="btn btn-sm btn-dark position-absolute end-0 mt-1 me-1 copy-btn" data-idx="${idx}">Copy</button>
                                    <pre style="border: 1px solid #ccc; border-radius: 5px; padding: 10px; background-color: #f8f9fa;"><code class="language-${language}" data-idx="${idx}">${escapeHtml(code)}</code></pre>
                                </div>`;
                            finalHtml = finalHtml.replaceAll(placeholder, codeBlock);
                        });
                        renderGuideContent(finalHtml, topicName, currentIndex);
                        setupCopyButtons();
                    });
                } else {
                    renderGuideContent(stepsHtml, topicName, currentIndex);
                }
            });
        });

        function renderGuideContent(html, topicName, index) {
            guideContent.innerHTML = `
                <div class="card">
                    <div class="card-body">
                        <h4 class="mb-3">${topicName}</h4>
                        <div>${html}</div>
                        <div class="d-flex justify-content-between mt-4">
                            <button id="prevTopic" class="btn btn-secondary">&laquo; Sebelumnya</button>
                            <button id="nextTopic" class="btn btn-secondary">Berikutnya &raquo;</button>
                        </div>
                    </div>
                </div>`;

            document.getElementById('prevTopic')?.addEventListener('click', () => {
                if (index > 0) topicLinks[index - 1]?.click();
            });

            document.getElementById('nextTopic')?.addEventListener('click', () => {
                if (index < topicLinks.length - 1) topicLinks[index + 1]?.click();
            });

            document.getElementById('prevTopic').style.visibility = index > 0 ? 'visible' : 'hidden';
            document.getElementById('nextTopic').style.visibility = index < topicLinks.length - 1 ? 'visible' : 'hidden';
        }

        function decodeHtml(html) {
            const txt = document.createElement('textarea');
            txt.innerHTML = html;
            return txt.value;
        }

        function escapeHtml(str) {
            return String(str)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        function setupCopyButtons() {
            document.querySelectorAll('.copy-btn').forEach(btn => {
                btn.addEventListener('click', function () {
                    const idx = this.dataset.idx;
                    const code = document.querySelector(`code[data-idx="${idx}"]`);
                    if (code) {
                        navigator.clipboard.writeText(code.innerText).then(() => {
                            this.textContent = 'Copied!';
                            setTimeout(() => this.textContent = 'Copy', 1500);
                        });
                    }
                });
            });
        }
    });
</script>
@endpush
