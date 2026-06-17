<?php

/**
 * Tujuan: Handler error default untuk loader fungsi (Funct).
 * Cara pakai: Funct::loadFileDefault() akan me-load file ini; panggil functErrorHandleDefault($data).
 * Dependency: Tidak ada dependency eksternal.
 * Catatan standalone: Bisa di-require langsung untuk menyediakan fungsi globalnya.
 */

function functErrorHandleDefault($data)
{
	error_log("[Funct] {$data}", 0);
}
