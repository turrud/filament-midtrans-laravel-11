<!-- Modal -->
<div style="font-family: sans-serif;" id="bookingModal" class="hidden opacity-0 modal-transition fixed inset-0 bg-gray-400 bg-opacity-10 backdrop-blur-sm overflow-y-auto h-full w-full">
    <div id="modalContent" style="margin-bottom: 150px" class="modal-content-transition relative flex flex-col top-20 mx-auto p-5 border w-full max-w-xl shadow-lg rounded-md bg-white/95">
        <!-- Modal Close Button -->
        <div class="flex justify-end">
            <button
                onclick="closeModal()"
                class="text-gray-400 hover:text-gray-500 focus:outline-none transform transition-transform duration-200 hover:scale-110"
            >
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Form Content -->
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Booking Information</h2>

        <form class="space-y-6" action="{{ route('order.store', ['id' => $package->id]) }}" method="POST">
            @csrf
            <!-- Name Field -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                    Full Name
                </label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-1 focus:ring-gray-500 focus:border-gray-500"
                    required
                >
            </div>
            <!-- Phone Field -->
            <div>
                <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">
                    Phone Number
                </label>
                <input
                    type="tel"
                    id="phone"
                    name="phone"
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-1 focus:ring-gray-500 focus:border-gray-500"
                    required
                >
            </div>
            <!-- Address Field -->
            <div>
                <label for="address" class="block text-sm font-medium text-gray-700 mb-1">
                    Address
                </label>
                <textarea
                    id="address"
                    name="address"
                    rows="3"
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-gray-500 focus:border-gray-500"
                    required
                ></textarea>
            </div>
            <!-- Person Field -->
            <div>
                <label for="quantity" class="block text-sm font-medium text-gray-700 mb-1">
                    Number of Person
                </label>
                <input
                    type="number"
                    id="quantity"
                    name="quantity"
                    min="1"
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-gray-500 focus:border-gray-500"
                    required
                >
            </div>
            <!-- Check-in and Check-out Dates -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="checkin" class="block text-sm font-medium text-gray-700 mb-1">
                        Check-in Date
                    </label>
                    <input
                        type="date"
                        id="checkin"
                        name="checkin"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-gray-500 focus:border-gray-500"
                        required
                    >
                </div>

                <div>
                    <label for="checkout" class="block text-sm font-medium text-gray-700 mb-1">
                        Check-out Date
                    </label>
                    <input
                        type="date"
                        id="checkout"
                        name="checkout"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-gray-500 focus:border-gray-500"
                        required
                    >
                </div>
            </div>
            <!-- Durasi -->
            <div>
                <label for="duration" class="block text-sm font-medium text-gray-700 mb-1">
                    Durasi
                </label>
                <input
                    type="number"
                    id="duration"
                    name="duration"
                    value="1"
                    min="1"
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-gray-500 focus:border-gray-500"
                    readonly
                    required
                >
            </div>
            <!-- Submit Button -->
            <div class="pt-4">
                <button
                    type="submit"
                    class="w-full bg-gray-600 text-white px-6 py-3 rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors"
                >
                    Submit Booking
                </button>
            </div>
        </form>
    </div>
</div>
    <script>
        // Ambil elemen input
        const checkinInput = document.getElementById('checkin');
        const checkoutInput = document.getElementById('checkout');
        const durationInput = document.getElementById('duration');

        // Set default value durasi
        durationInput.value = "1";

        // Fungsi untuk mendapatkan tanggal besok dari tanggal yang dipilih
        function getNextDay(date) {
            const nextDay = new Date(date);
            nextDay.setDate(nextDay.getDate() + 1);
            return nextDay.toISOString().split('T')[0];
        }

        // Fungsi untuk menghitung durasi
        function calculateDuration() {
            const checkinDate = new Date(checkinInput.value);
            const checkoutDate = new Date(checkoutInput.value);

            if (checkinInput.value && checkoutInput.value) {
                // Hitung selisih dalam milisecond dan konversi ke hari
                const diffTime = checkoutDate - checkinDate;
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

                if (diffDays >= 1) {
                    durationInput.value = diffDays;
                } else {
                    // Jika checkout sebelum atau sama dengan checkin, set checkout ke hari berikutnya
                    checkoutInput.value = getNextDay(checkinInput.value);
                    durationInput.value = "1";
                }
            }
        }

        // Set default checkout dan minimum date saat checkin dipilih
        checkinInput.addEventListener('change', function() {
            // Set minimum date untuk checkout
            checkoutInput.min = this.value;

            // Set default checkout ke hari berikutnya
            checkoutInput.value = getNextDay(this.value);

            calculateDuration();
        });

        // Hitung durasi ketika checkout berubah
        checkoutInput.addEventListener('change', calculateDuration);
    </script>
