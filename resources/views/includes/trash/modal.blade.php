<!-- Modal -->
<div class="modal fade" id="modal-{{ $house->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">ATTENZIONE</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Sei sicuro di voler cancellare definitivamente {{ $house->name }}?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                <form action="{{ route('user.houses.drop', $house->id) }}" method="post">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger">Cancella definitivamente</button>
                </form>
            </div>
        </div>
    </div>
</div>
