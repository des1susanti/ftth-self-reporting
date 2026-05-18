<div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
    <h3 class="text-lg font-bold mb-4">Update Progress Perbaikan</h3>

    <form action="{{ route('technician.update-status', $ticket->id) }}" method="POST">
        @csrf
        @method('PATCH')

        <div class="grid grid-cols-4 gap-4 mb-8">
            <button name="progress_status" value="otw" class="{{ $ticket->progress_status == 'otw' ? 'bg-blue-600 text-white' : 'bg-gray-100' }} p-3 rounded-xl text-xs font-bold uppercase">
                🚀 Perjalanan
            </button>
            <button name="progress_status" value="arrived" class="{{ $ticket->progress_status == 'arrived' ? 'bg-blue-600 text-white' : 'bg-gray-100' }} p-3 rounded-xl text-xs font-bold uppercase">
                📍 Sampai Lokasi
            </button>
            <button name="progress_status" value="repairing" class="{{ $ticket->progress_status == 'repairing' ? 'bg-blue-600 text-white' : 'bg-gray-100' }} p-3 rounded-xl text-xs font-bold uppercase">
                🛠️ Proses Perbaikan
            </button>
            <button type="button" onclick="showFinalForm()" class="bg-green-500 text-white p-3 rounded-xl text-xs font-bold uppercase">
                ✅ Selesai
            </button>
        </div>

        <div id="final-form" class="space-y-4 hidden">
            <div>
                <label class="block text-sm font-semibold mb-1">Penyebab Gangguan</label>
                <textarea name="penyebab" class="w-full border rounded-lg p-2" placeholder="Contoh: Kabel FO putus terkena dahan pohon"></textarea>
            </div>
            <div>
                <label class="block text-sm font-semibold mb-1">Action (Tindakan Tim)</label>
                <textarea name="action_taken" class="w-full border rounded-lg p-2" placeholder="Contoh: Splicing kabel dan penggantian patchcord"></textarea>
            </div>
            <button type="submit" name="progress_status" value="resolved" class="w-full bg-green-600 text-white py-3 rounded-xl font-bold">
                SUBMIT & SELESAIKAN TIKET
            </button>
        </div>
    </form>
</div>

<script>
    function showFinalForm() {
        document.getElementById('final-form').classList.remove('hidden');
    }
</script>