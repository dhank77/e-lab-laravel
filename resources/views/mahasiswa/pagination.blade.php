@if ($pages->hasPages())
    <nav class="lab-pages" aria-label="Navigasi halaman">
        @if ($pages->previousPageUrl()) <a href="{{ $pages->previousPageUrl() }}">Sebelumnya</a> @endif
        @if ($pages->nextPageUrl()) <a href="{{ $pages->nextPageUrl() }}">Berikutnya</a> @endif
    </nav>
@endif
