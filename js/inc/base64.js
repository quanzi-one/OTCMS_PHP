/* JS版base64编解码算法。示例:
 * b64 = base64encode(data);
 * data = base64decode(b64);
 */
var base64EncodeChars = [
	"A", "B", "C", "D", "E", "F", "G", "H",
	"I", "J", "K", "L", "M", "N", "O", "P",
	"Q", "R", "S", "T", "U", "V", "W", "X",
	"Y", "Z", "a", "b", "c", "d", "e", "f",
	"g", "h", "i", "j", "k", "l", "m", "n",
	"o", "p", "q", "r", "s", "t", "u", "v",
	"w", "x", "y", "z", "0", "1", "2", "3",
	"4", "5", "6", "7", "8", "9", "+", "/"
];

var base64DecodeChars = [
	-1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1,
	-1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1,
	-1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, 62, -1, -1, -1, 63,
	52, 53, 54, 55, 56, 57, 58, 59, 60, 61, -1, -1, -1, -1, -1, -1,
	-1,  0,  1,  2,  3,  4,  5,  6,  7,  8,  9, 10, 11, 12, 13, 14,
	15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, -1, -1, -1, -1, -1,
	-1, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40,
	41, 42, 43, 44, 45, 46, 47, 48, 49, 50, 51, -1, -1, -1, -1, -1
];

function base64encode(str) {
	var out, i, j, len;
	var c1, c2, c3;

	len = str.length;
	i = j = 0;
	out = [];
	while (i < len) {
		c1 = str.charCodeAt(i++) & 0xff;
		if (i == len)
		{
			out[j++] = base64EncodeChars[c1 >> 2];
			out[j++] = base64EncodeChars[(c1 & 0x3) << 4];
			out[j++] = "==";
			break;
		}
		c2 = str.charCodeAt(i++) & 0xff;
		if (i == len)
		{
			out[j++] = base64EncodeChars[c1 >> 2];
			out[j++] = base64EncodeChars[((c1 & 0x03) << 4) | ((c2 & 0xf0) >> 4)];
			out[j++] = base64EncodeChars[(c2 & 0x0f) << 2];
			out[j++] = "=";
			break;
		}
		c3 = str.charCodeAt(i++) & 0xff;
		out[j++] = base64EncodeChars[c1 >> 2];
		out[j++] = base64EncodeChars[((c1 & 0x03) << 4) | ((c2 & 0xf0) >> 4)];
		out[j++] = base64EncodeChars[((c2 & 0x0f) << 2) | ((c3 & 0xc0) >> 6)];
		out[j++] = base64EncodeChars[c3 & 0x3f];
	}
	return out.join('');
}

function base64decode(str) {
	var c1, c2, c3, c4;
	var i, j, len, out;

	len = str.length;
	i = j = 0;
	out = [];
	while (i < len) {
		/* c1 */
		do {
			c1 = base64DecodeChars[str.charCodeAt(i++) & 0xff];
		} while (i < len && c1 == -1);
		if (c1 == -1) break;

		/* c2 */
		do {
			c2 = base64DecodeChars[str.charCodeAt(i++) & 0xff];
		} while (i < len && c2 == -1);
		if (c2 == -1) break;

		out[j++] = String.fromCharCode((c1 << 2) | ((c2 & 0x30) >> 4));

		/* c3 */
		do {
			c3 = str.charCodeAt(i++) & 0xff;
			if (c3 == 61) return out.join('');
			c3 = base64DecodeChars[c3];
		} while (i < len && c3 == -1);
		if (c3 == -1) break;

		out[j++] = String.fromCharCode(((c2 & 0x0f) << 4) | ((c3 & 0x3c) >> 2));

		/* c4 */
		do {
			c4 = str.charCodeAt(i++) & 0xff;
			if (c4 == 61) return out.join('');
			c4 = base64DecodeChars[c4];
		} while (i < len && c4 == -1);
		if (c4 == -1) break;
		out[j++] = String.fromCharCode(((c3 & 0x03) << 6) | c4);
	}
	return out.join('');
}