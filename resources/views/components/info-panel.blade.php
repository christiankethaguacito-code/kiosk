<!-- filepath: c:\xampp\htdocs\Navi\resources\views\components\info-panel.blade.php -->
<div class="side-panel" id="infoPanel">
  <header>
    <h2 id="panelTitle">{{ $building->name ?? 'Building Info' }}</h2>
    <button class="close-btn" id="closePanel">&times;</button>
  </header>
  <div id="panelBody">
    <p>{{ $building->description ?? 'No description available.' }}</p>
    @if(!empty($offices) && count($offices) > 0)
      <h3>Offices:</h3>
      <ul>
        @foreach($offices as $office)
          <li><strong>{{ $office->name }}</strong>: {{ $office->services }}</li>
        @endforeach
      </ul>
    @else
      <p>No offices found.</p>
    @endif
  </div>
</div>