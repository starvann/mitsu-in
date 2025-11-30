<x-base title="Ujian {{ $exam->judul }}">
  <div>
    <h1>{{ $exam->judul }}</h1>
    <p>{{ $exam->deskripsi }}</p>
  </div>
  <div id="soal" data-idx="0">
    <p>{{ $question->soal }}</p>
    <div id="jwbn">
      @foreach($question->jawaban as $i => $jwb)
      <label for="jwbn-{{ $i }}">
        <input type="radio" name="jwbn-1" id="jwbn-{{ $i }}">
        {{ $jwb }}
      </label>
      @endforeach
    </div>
    <div>
      <button type="button" id="prev">&laquo;</button>
      <button type="button" id="next">&raquo;</button>
    </div>
  </div>
  <div id="examNav">
    @for($i = 0; $i < $questions_count; $i++)
    <button type="button" data-idx="{{ $i }}">{{ $i + 1 }}</button>
    @endforeach
  </div>
  <script>
    
  </script>
</x-base>