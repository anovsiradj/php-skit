# CURL — cURL Wrapper Domain Knowledge

## PHP curl quirk: CURLOPT_POSTFIELDS array auto-switch
- Passing array to CURLOPT_POSTFIELDS → cURL internally switches Content-Type to multipart/form-data (own boundary).
- Does NOT produce application/x-www-form-urlencoded even if TYPE_URLE is intended.
- To get true urlencoded: pass http_build_query($params) as STRING, not array.
- CURLFile objects in array → triggers multipart automatically. No explicit boundary/config needed.
- THEREFORE: manual multipart() builder is redundant. Retained as @internal escape hatch.

## Design intent
- 1 instance = 1 session. resHeaders accumulate. reqHeaders accumulate.
- exec() does not return result. Use data() instead (transforms JSON to array).
- stderr/<file> captures verbose curl log. stdout/<file> appends raw response + 3 blank lines as separator.
- TYPE_<4-letter> naming convention: URLE (urlencoded), MPFD (multipart), JSON, TEXT.

## file() MIME detection
- Uses mime_content_type() if ext-fileinfo loaded; falls back to extension map.
- Falls back to application/octet-stream if unknown extension.
- postname auto-set from basename($path).
