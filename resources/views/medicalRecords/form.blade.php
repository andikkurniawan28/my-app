<div class="mb-3">
    <label for="date" class="form-label">Tanggal</label>
    <input type="date" name="date" id="date"
           class="form-control @error('date') is-invalid @enderror"
           value="{{ old('date', $medicalRecord->date ?? '') }}" required>
    @error('date')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

<div class="mb-3">
    <label for="weight" class="form-label">Berat (kg)</label>
    <input type="number" step="0.1" name="weight" id="weight"
           class="form-control"
           value="{{ old('weight', $medicalRecord->weight ?? '') }}">
</div>

<div class="mb-3">
    <label for="height" class="form-label">Tinggi (cm)</label>
    <input type="number" step="0.1" name="height" id="height"
           class="form-control"
           value="{{ old('height', $medicalRecord->height ?? '') }}">
</div>

<div class="mb-3">
    <label class="form-label">Tekanan Darah</label>
    <div class="d-flex gap-2">
        <input type="number" name="blood_pressure_systolic" class="form-control" placeholder="Sistolik"
               value="{{ old('blood_pressure_systolic', $medicalRecord->blood_pressure_systolic ?? '') }}">
        <input type="number" name="blood_pressure_diastolic" class="form-control" placeholder="Diastolik"
               value="{{ old('blood_pressure_diastolic', $medicalRecord->blood_pressure_diastolic ?? '') }}">
    </div>
</div>

<div class="mb-3">
    <label for="temperature" class="form-label">Suhu (°C)</label>
    <input type="number" step="0.1" name="temperature" id="temperature"
           class="form-control"
           value="{{ old('temperature', $medicalRecord->temperature ?? '') }}">
</div>

<div class="mb-3">
    <label for="pulse" class="form-label">Denyut Nadi (per menit)</label>
    <input type="number" name="pulse" id="pulse" class="form-control"
           value="{{ old('pulse', $medicalRecord->pulse ?? '') }}">
</div>

<hr>

<h5>Gula Darah</h5>
<div class="mb-3">
    <label for="blood_sugar_fasting" class="form-label">Puasa</label>
    <input type="number" step="0.1" name="blood_sugar_fasting" id="blood_sugar_fasting"
           class="form-control"
           value="{{ old('blood_sugar_fasting', $medicalRecord->blood_sugar_fasting ?? '') }}">
</div>
<div class="mb-3">
    <label for="blood_sugar_random" class="form-label">Sewaktu</label>
    <input type="number" step="0.1" name="blood_sugar_random" id="blood_sugar_random"
           class="form-control"
           value="{{ old('blood_sugar_random', $medicalRecord->blood_sugar_random ?? '') }}">
</div>
<div class="mb-3">
    <label for="hba1c" class="form-label">HbA1c (%)</label>
    <input type="number" step="0.1" name="hba1c" id="hba1c"
           class="form-control"
           value="{{ old('hba1c', $medicalRecord->hba1c ?? '') }}">
</div>

<hr>

<h5>Profil Lipid</h5>
<div class="mb-3">
    <label for="cholesterol_total" class="form-label">Kolesterol Total</label>
    <input type="number" step="0.1" name="cholesterol_total" id="cholesterol_total"
           class="form-control"
           value="{{ old('cholesterol_total', $medicalRecord->cholesterol_total ?? '') }}">
</div>
<div class="mb-3">
    <label for="cholesterol_hdl" class="form-label">HDL</label>
    <input type="number" step="0.1" name="cholesterol_hdl" id="cholesterol_hdl"
           class="form-control"
           value="{{ old('cholesterol_hdl', $medicalRecord->cholesterol_hdl ?? '') }}">
</div>
<div class="mb-3">
    <label for="cholesterol_ldl" class="form-label">LDL</label>
    <input type="number" step="0.1" name="cholesterol_ldl" id="cholesterol_ldl"
           class="form-control"
           value="{{ old('cholesterol_ldl', $medicalRecord->cholesterol_ldl ?? '') }}">
</div>
<div class="mb-3">
    <label for="triglycerides" class="form-label">Trigliserida</label>
    <input type="number" step="0.1" name="triglycerides" id="triglycerides"
           class="form-control"
           value="{{ old('triglycerides', $medicalRecord->triglycerides ?? '') }}">
</div>

<hr>

<h5>Fungsi Ginjal</h5>
<div class="mb-3">
    <label for="creatinine" class="form-label">Kreatinin</label>
    <input type="number" step="0.1" name="creatinine" id="creatinine"
           class="form-control"
           value="{{ old('creatinine', $medicalRecord->creatinine ?? '') }}">
</div>
<div class="mb-3">
    <label for="bun" class="form-label">BUN</label>
    <input type="number" step="0.1" name="bun" id="bun"
           class="form-control"
           value="{{ old('bun', $medicalRecord->bun ?? '') }}">
</div>
<div class="mb-3">
    <label for="egfr" class="form-label">eGFR</label>
    <input type="number" step="0.1" name="egfr" id="egfr"
           class="form-control"
           value="{{ old('egfr', $medicalRecord->egfr ?? '') }}">
</div>

<hr>

<div class="mb-3">
    <label for="notes" class="form-label">Catatan</label>
    <textarea name="notes" id="notes" rows="3" class="form-control">{{ old('notes', $medicalRecord->notes ?? '') }}</textarea>
</div>
