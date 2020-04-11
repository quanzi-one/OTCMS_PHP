var	_default_img_dir = 'tools/keyBoard/';
var vbk_images=new Array();
function vbk_preloadimages(){
	if (document.images){
		for (i=0;i<vbk_preloadimages.arguments.length;i++){
			vbk_images[i]=new Image()
			vbk_images[i].src=_default_img_dir+vbk_preloadimages.arguments[i]
		}
	}
}
vbk_preloadimages("key_normal.gif","key_cap.gif","key_enter.gif","key_space.gif","key_back.gif","key_t0.gif","key_t1.gif","key_t2.gif","key_tab.gif","key_shift_l.gif","key_shift_r.gif","keyboard.gif","key_normal_down.gif","key_cap_down.gif","key_enter_down.gif","key_space_down.gif","key_back_down.gif");

function VKeyboard(container_id, callback_ref, create_numpad, font_name,
                   font_size, font_color, dead_color, bg_color, key_color,
                   sel_item_color, border_color, inactive_border_color,
                   inactive_key_color, lang_sel_brd_color, show_click,
                   click_font_color, click_bg_color, click_border_color,
                   do_embed)
{
  return this._construct(container_id, callback_ref, create_numpad,
                         font_name, font_size, font_color, dead_color,
                         bg_color, key_color, sel_item_color, border_color,
                         inactive_border_color, inactive_key_color,
                         lang_sel_brd_color, show_click, click_font_color,
                         click_bg_color, click_border_color, do_embed);
}

VKeyboard.prototype = {

  kbArray: [],

  _get_event_source: function(event)
  {
    var e = event || window.event;
    return e.srcElement || e.target;
  },

  _setup_event: function(elem, eventType, handler)
  {
    return (elem.attachEvent ? elem.attachEvent("on" + eventType, handler) : ((elem.addEventListener) ? elem.addEventListener(eventType, handler, false) : null));
  },

  _detach_event: function(elem, eventType, handler)
  {
    return (elem.detachEvent ? elem.detachEvent("on" + eventType, handler) : ((elem.removeEventListener) ? elem.removeEventListener(eventType, handler, false) : null));
  },

  _start_flash: function(in_el)
  {
	if(in_el.style.backgroundImage.indexOf('_down')<0)
  	in_el.style.backgroundImage=in_el.style.backgroundImage.replace('.gif','_down.gif');
  	return;
    function getColor(str, posOne, posTwo)
    {
      if(/rgb\((\d+),\s(\d+),\s(\d+)\)/.exec(str)) // try to detect Mozilla-style rgb value.
      {
        switch(posOne)
        {
          case 1: return parseInt(RegExp.$1, 10);
          case 2: return parseInt(RegExp.$2, 10);
          case 3: return parseInt(RegExp.$3, 10);
          default: return 0;
        }
      }
      else // standard (#xxxxxx or #xxx) way
        return str.length == 4 ? parseInt(str.substr(posOne, 1) + str.substr(posOne, 1), 16) : parseInt(str.substr(posTwo, 2), 16);
    }

    function getR(color_string)
    { return getColor(color_string, 1, 1); }

    function getG(color_string)
    { return getColor(color_string, 2, 3); }

    function getB(color_string)
    { return getColor(color_string, 3, 5); }

    var el = in_el.time ? in_el : (in_el.company && in_el.company.time ? in_el.company : null);
    if(el)
    {
      el.time = 0;
      clearInterval(el.timer);
    }

    var vkb = this;
    var ftc = vkb.fontcolor, bgc = vkb.keycolor, brc = vkb.bordercolor;

    // Special fixes for simple/dead/modifier keys:

    if(in_el.dead)
      ftc = vkb.deadcolor;

    if(((in_el.innerHTML == "Shift") && vkb.Shift) || ((in_el.innerHTML == "Caps") && vkb.Caps) || ((in_el.innerHTML == "AltGr") && vkb.AltGr))
      bgc = vkb.lic;

    // Extract base color values:
    var fr = getR(ftc), fg = getG(ftc), fb = getB(ftc);
    var kr = getR(bgc), kg = getG(bgc), kb = getB(bgc);
    var br = getR(brc), bg = getG(brc), bb = getB(brc);

    // Extract flash color values:
    var f_r = getR(vkb.cfc), f_g = getG(vkb.cfc), f_b = getB(vkb.cfc);
    var k_r = getR(vkb.cbg), k_g = getG(vkb.cbg), k_b = getB(vkb.cbg);
    var b_r = getR(vkb.cbr), b_g = getG(vkb.cbr), b_b = getB(vkb.cbr);
    var _shift_colors = function()
    {
      function dec2hex(dec)
      {
        var hexChars = "0123456789ABCDEF";
        var a = dec % 16;
        var b = (dec - a) / 16;

        return hexChars.charAt(b) + hexChars.charAt(a) + "";
      }

      in_el.time = !in_el.time ? 10 : (in_el.time - 1);

      function calc_color(start, end)
      { return (end - (in_el.time / 10) * (end - start)); }

      var t_f_r = calc_color(f_r, fr), t_f_g = calc_color(f_g, fg), t_f_b = calc_color(f_b, fb);
      var t_k_r = calc_color(k_r, kr), t_k_g = calc_color(k_g, kg), t_k_b = calc_color(k_b, kb);
      var t_b_r = calc_color(b_r, br), t_b_g = calc_color(b_g, bg), t_b_b = calc_color(b_b, bb);

      function setStyles(style)
      {
        style.color = "#" + dec2hex(t_f_r) + dec2hex(t_f_g) + dec2hex(t_f_b);
        style.borderColor = "#" + dec2hex(t_b_r) + dec2hex(t_b_g) + dec2hex(t_b_b);
        style.backgroundColor = "#" + dec2hex(t_k_r) + dec2hex(t_k_g) + dec2hex(t_k_b);
      }

      var first = (in_el == vkb.mod[4]) ? false : true, is = in_el.style, cs = in_el.company ? in_el.company.style : null;

      if(cs && first)
        setStyles(cs);

      setStyles(is);

      if(cs)
      {
        if(!first)
        {
          setStyles(cs);
          is.borderBottomColor = "#" + dec2hex(t_k_r) + dec2hex(t_k_g) + dec2hex(t_k_b);
        }
        else
          cs.borderBottomColor = "#" + dec2hex(t_k_r) + dec2hex(t_k_g) + dec2hex(t_k_b);
      }

      if(!in_el.time)
      {
        clearInterval(in_el.timer);
        return;
      }
    };

    _shift_colors();

    in_el.timer = window.setInterval(_shift_colors, 50);
  },

  _setup_style: function(obj, top, left, width, height, position, text_align, line_height, font_size, font_weight, padding_left, padding_right)
  {
    var os = obj.style;
    if(top)    os.top = top;
    if(left)   os.left = left;
    if(width)  os.width = width;
    if(height) os.height = height;

    if(position) os.position = position;

    if(text_align)  os.textAlign  = text_align;
    if(line_height) os.lineHeight = line_height;
    if(font_size)   os.fontSize   = 12;

    os.fontWeight = font_weight || "lighter";
    os.fontFamily = "Arial,Verdana,	 New";
	  
    if(padding_left)  os.paddingLeft  = padding_left;
    if(padding_right) os.paddingRight = padding_right;
  },

  _setup_key: function(parent, id, top, left, width, height, text_align, line_height, font_size, font_weight, padding_left, padding_right, backImage)
  {
    
    var _id = this.Cntr.id + id;
    var exists = document.getElementById(_id);
		
    var key = exists ? exists.parentNode : document.createElement("DIV");
    this._setup_style(key, top, left, width, height, "absolute");
	//	key.style.cursor="pointer";
    var key_sub = exists || document.createElement("DIV");
    key.appendChild(key_sub); parent.appendChild(key);

    this._setup_style(key_sub, "", "", "", line_height, "relative", text_align, line_height, font_size, font_weight, padding_left, padding_right);
    key_sub.id = _id;
    var bi = backImage?backImage:'url(key_normal.gif)';
    bi = bi.replace('url(','url('+_default_img_dir);
		key_sub.style.backgroundImage=bi;
		key_sub.style.backgroundRepeat='no-repeat';
		
    return key_sub;
  },

  _findX: function(obj)
  { return (obj && obj.parentNode) ? parseFloat(obj.parentNode.offsetLeft) : 0; },

  _findY: function(obj)
  { return (obj && obj.parentNode) ? parseFloat(obj.parentNode.offsetTop) : 0; },

  _findW: function(obj)
  { return (obj && obj.parentNode) ? parseFloat(obj.parentNode.offsetWidth) -2: 0; },

  _findH: function(obj)
  { return (obj && obj.parentNode) ? parseFloat(obj.parentNode.offsetHeight) : 0; },

  _construct: function(container_id, callback_ref, create_numpad, font_name, font_size, font_color, dead_color,
                       bg_color, key_color, sel_item_color, border_color, inactive_border_color, inactive_key_color,
                       lang_sel_brd_color, show_click, click_font_color, click_bg_color, click_border_color, do_embed)
  {
    var exists  = (this.Cntr != undefined), ct = exists ? this.Cntr : document.getElementById(container_id);
    var changed = (font_size && (font_size != this.fontsize));

    this._Callback = ((typeof(callback_ref) == "function") && ((callback_ref.length == 1) || (callback_ref.length == 2))) ? callback_ref : (this._Callback || null);

    var ff = font_name || this.fontname || "";
    var fs = font_size || this.fontsize || "14px";

    var fc = font_color   || this.fontcolor   || "#000";
    var dc = dead_color   || this.deadcolor   || "#F00";
    var bg = bg_color     || this.bgcolor     || "#FFF";
    var kc = key_color    || this.keycolor    || "#FFF";
    var bc = border_color || this.bordercolor || "#777";

    this.lic = sel_item_color        || this.lic || "#DDD";
    this.ibc = inactive_border_color || this.ibc || "#CCC";
    this.ikc = inactive_key_color    || this.ikc || "#FFF";
    this.lsc = lang_sel_brd_color    || this.lsc || "#F77";

    this.cfc = click_font_color   || this.cfc || "#CC3300";
    this.cbg = click_bg_color     || this.cbg || "#FF9966";
    this.cbr = click_border_color || this.cbr || "#CC3300";

    this.sc = (show_click == undefined) ? ((this.sc == undefined) ? false : this.sc) : show_click;

    this.fontname = ff, this.fontsize = fs, this.fontcolor = fc;
    this.bgcolor = bg,  this.keycolor = kc, this.deadcolor = dc, this.bordercolor = bc;
		
    if(!exists)
    {
      this.Cntr = ct;
      this.Caps = this.Shift = this.AltGr = false;

      this.DeadAction = []; this.DeadAction[0] = this.DeadAction[1] = null;
      this.keys = [], this.mod = [], this.pad = [];

      VKeyboard.prototype.kbArray[container_id] = this;
    }

    var kb = exists ? ct.childNodes[0] : document.createElement("DIV");

    if(!exists)
    {
      ct.appendChild(kb);
      ct.style.display = "block";
      ct.style.zIndex  = 999;
      if(do_embed)
        ct.style.position = "relative";
      else
      {
        ct.style.position = "absolute";

        // Many thanks to Peter-Paul Koch (www.quirksmode.org) for the find-pos-X/find-pos-Y code.
        var initX = 0, ct_ = ct;
        if(ct_.offsetParent)
        {
          while(ct_.offsetParent)
          {
            initX += ct_.offsetLeft;
            ct_ = ct_.offsetParent;
          }
        }
        else if(ct_.x)
          initX += ct_.x;

        var initY = 0; ct_ = ct;
        if(ct_.offsetParent)
        {
          while(ct_.offsetParent)
          {
            initY += ct_.offsetTop;
            ct_ = ct_.offsetParent;
          }
        }
        else if(ct_.y)
          initY += ct_.y;

        ct.style.top = initY + "px", ct.style.left = initX +"px";
      }

      kb.style.position = "relative";
      kb.style.top      = "0px", kb.style.left = "0px";
      kb.style.backgroundImage = 'url('+_default_img_dir+'keyboard.gif)';
    }
		kb.style.padding = "5px";
    kb.style.border = "0px solid " + bc;

    var kb_main = exists ? kb.childNodes[0] : document.createElement("DIV"), ks = kb_main.style;
 	
    if(!exists)
    {
      kb.appendChild(kb_main);

      ks.position = "relative";
      ks.width    = "1px";
      ks.cursor   = "default";
    }

    // Disable content selection:
    this._setup_event(kb_main, "selectstart", function(event) { return false; });
    this._setup_event(kb_main, "mousedown",   function(event) { if(event.preventDefault) event.preventDefault(); return false; });

    ks.fontFamily = ff, ks.backgroundColor = bg;

    if(!exists || changed)
    {
      var mag = parseFloat(fs) / 14.0, cell = Math.floor(25.0 * mag), dcell = 2 * cell;
      var cp = String(cell) + "px", lh = String(cell - 2.0) + "px";
			var cp_t0 = "45px";

      var prevX = 0, prevY = 1, prevW = 0, prevH = 0;

      // Convenience strings:
      var c = "center", n = "normal", r = "right", l = "left", e = "&nbsp;", pad = String(4 * mag) + "px";

      // Number row:

      var key;
      for(var i = 0; i < 13; i++)
      {
        this.keys[i] = key = this._setup_key(kb_main, "___key" + String(i), "1px", (prevX + prevW + 1) + "px", cp, cp, c, lh, fs);

        prevX = this._findX(key), prevW = this._findW(key);
      }

      prevY = this._findY(key);
      prevH = this._findH(key)-2; // universal key height

      var kb_kbp = this._setup_key(kb_main, "___kbp", "1px", (prevX + prevW + 1) + "px", (2.1 * cell) + "px", cp, r, lh, fs, n, "", pad, "url(key_back.gif)");
      kb_kbp.innerHTML = "";
      kb_kbp.subst="BackSpace";
      this.mod[0] = kb_kbp;

      // Top row:

      var kb_tab = this._setup_key(kb_main, "___tab", (prevY + prevH + 1) + "px", "1px", (1.48 * cell - 1) + "px", cp, l, lh, fs, n, "", pad, "url(key_tab.gif)");
      kb_tab.innerHTML = "";
      kb_tab.subst = "";
      this.mod[1] = kb_tab;

      prevX = this._findX(kb_tab), prevW = this._findW(kb_tab), prevY = this._findY(kb_tab);
			
      for(; i < 26; i++)
      {
      	var bi = null;
      	if(i==25){
      		this.keys[i] = key = this._setup_key(kb_main, "___key" + String(i), prevY + "px", (prevX + prevW + 1) + "px", cp_t0, cp, c, lh, fs, null, "", null, "url(key_t0.gif)");
      	}else{
        	this.keys[i] = key = this._setup_key(kb_main, "___key" + String(i), prevY + "px", (prevX + prevW + 1) + "px", cp, cp, c, lh, fs);
        }
        prevX = this._findX(key), prevW = this._findW(key);
      }

      this.kbpH = this._findX(kb_kbp) + this._findW(kb_kbp);
		
      // Home row:

      var kb_caps = this._setup_key(kb_main, "___caps", (prevY + prevH + 1) + "px", "1px", dcell-6 + "px", cp, l, lh, fs, n, "",pad,'url(key_cap.gif)');
      kb_caps.innerHTML = "";
      kb_caps.subst = "Caps";
      this.mod[2] = kb_caps;

      prevX = this._findX(kb_caps), prevW = this._findW(kb_caps), prevY = this._findY(kb_caps);

      for(; i < 37; i++)
      {
        this.keys[i] = key = this._setup_key(kb_main, "___key" + String(i), prevY + "px", (prevX + prevW + 1) + "px", cp, cp, c, lh, fs);

        prevX = this._findX(key), prevW = this._findW(key);
      }

      prevY = this._findY(key);
      var s = prevX + prevW + 1;
		
      var kb_enter = this._setup_key(kb_main, "___enter_l", prevY + "px", s + "px", (this.kbpH - s+1) + "px", cp, r, lh, fs, n, "", pad,"url(key_enter.gif)");
      kb_enter.innerHTML = "";
      kb_enter.subst = "Enter";
      this.mod[3] = kb_enter;
			
      s = this._findX(this.keys[25]) + this._findW(this.keys[25]) + 1;

    //  var kb_enter_top = this._setup_key(kb_main, "___enter_top", this._findY(kb_tab) + "px", s + "px", (this.kbpH - s) + "px", cp, c, cp, fs);
    //  kb_enter_top.innerHTML = e;
    //  kb_enter_top.subst = "Enter";
    //  this.mod[4] = null;
    //  kb_enter_top.company = kb_enter;
    //  kb_enter.company = kb_enter_top;

      // Bottom row:

      var kb_shift = this._setup_key(kb_main, "___shift", (prevY + prevH + 1) + "px", "1px", (2.25 * cell) + "px", cp, l, lh, fs, n, "", pad,"url(key_shift_l.gif)");
      kb_shift.innerHTML = "";
      kb_shift.subst = "Shift";
      this.mod[5] = kb_shift;

      prevX = this._findX(kb_shift), prevW = this._findW(kb_shift), prevY = this._findY(kb_shift);
			
      for(; i < 47; i++)
      {
        this.keys[i] = key = this._setup_key(kb_main, "___key" + String(i), prevY + "px", (prevX + prevW + 1) + "px", cp, cp, c, lh, fs);

        prevX = this._findX(key), prevW = this._findW(key);
      }
      
      prevY = this._findY(key);

      var kb_shift_r = this._setup_key(kb_main, "___shift_r", prevY + "px", (prevX + prevW + 1) + "px", (this._findX(kb_kbp) + this._findW(kb_kbp) - prevX - prevW) + "px", cp, r, lh, fs, n, "", pad,"url(key_shift_r.gif)");
      kb_shift_r.innerHTML = "";
      kb_shift_r.subst = "Shift";
      this.mod[6] = kb_shift_r;

      // Language selector:

      var vcell = String(1.32 * cell) + "px";

      var kb_lang = this._setup_key(kb_main, "___lang", (prevY + prevH + 1) + "px", "1px", (1.43 * cell)+"px", cp, l, lh, fs, n, "",pad,"url(key_t1.gif)");
      kb_lang.subst = "Us";
      this.mod[7] = kb_lang;

      prevY = this._findY(kb_lang);

      ks.height = (prevY + prevH + 1) + "px";

      prevY += "px";

      var kb_res_1 = this._setup_key(kb_main, "___res_1", prevY, (this._findX(kb_lang) + this._findW(kb_lang) + 1) + "px", cp, cp, c, lh, fs);
      kb_res_1.innerHTML = e;
      this.mod[8] = kb_res_1;

      var kb_res_2 = this._setup_key(kb_main, "___res_2", prevY, (this._findX(kb_res_1) + this._findW(kb_res_1) + 1) + "px", cp, cp, c, lh, fs);
      kb_res_2.innerHTML = e;
      this.mod[9] = kb_res_2;

      var kb_space = this._setup_key(kb_main, "___space", prevY, (this._findX(kb_res_2) + this._findW(kb_res_2) + 1) + "px", (6.65 * cell) + "px", cp, c, lh, fs, "", "","","url(key_space.gif)");
      this.mod[10] = kb_space;

      var kb_alt_gr = this._setup_key(kb_main, "___alt_gr", prevY, (this._findX(kb_space) + this._findW(kb_space) + 1) + "px", cp, cp, c, lh, parseFloat(fs) * 0.786, n);
      kb_alt_gr.innerHTML = "";
      this.mod[11] = kb_alt_gr;

      var kb_res_3 = this._setup_key(kb_main, "___res_3", prevY, (this._findX(kb_alt_gr) + this._findW(kb_alt_gr) + 1) + "px", cp, cp, c, lh, fs);
      kb_res_3.innerHTML = e;
      this.mod[12] = kb_res_3;

      var kb_res_4 = this._setup_key(kb_main, "___res_4", prevY, (this._findX(kb_res_3) + this._findW(kb_res_3) + 1) + "px", cp, cp, c, lh, fs);
      kb_res_4.innerHTML = e;
      this.mod[13] = kb_res_4;

			var kb_res_5 = this._setup_key(kb_main, "___res_5", prevY, (this._findX(kb_res_4) + this._findW(kb_res_4) + 1) + "px", (1.77 * cell)+"px", cp, c, lh, fs,"","","","url(key_t2.gif)");
      kb_res_5.innerHTML = "";
      
      var w = this.kbpH + 1;

      // Numpad:

//      if((create_numpad == undefined) ? true : create_numpad)
//      {
//        var w2 = this._create_numpad(container_id, kb_main);
//        if(w2 > w) w = w2;
//      }

      kb.style.width = ks.width = w + "px";
    }

    this._refresh_layout(this.avail_langs[0][0]);
    return this;
  },
  _set_key_state: function(key, on, textcolor, bordercolor, bgcolor)
  {
    if(key)
    {
      var ks = key.style;
      if(ks)
      {
        if(textcolor) ks.color = textcolor;
        if(bordercolor) ks.border = "0px solid " + bordercolor;
        if(bgcolor) ks.backgroundColor = bgcolor;
      }

      this._detach_event(key, 'mousedown', this._generic_callback_proc);
      this._detach_event(key, 'mouseup', this._generic_callback_proc_mouseup);
      this._detach_event(key, 'mouseout', this._generic_callback_proc_mouseout);
      this._detach_event(key, 'mouseover', this._generic_callback_proc_mouseover);

      if(on){
        this._setup_event(key, 'mousedown', this._generic_callback_proc);
      	this._setup_event(key, 'mouseup', this._generic_callback_proc_mouseup);
      	this._setup_event(key, 'mouseout', this._generic_callback_proc_mouseout);
      	this._setup_event(key, 'mouseover', this._generic_callback_proc_mouseover);
      }
    }
  },

  _refresh_layout: function(layout)
  {
    if(!layout) layout = this.mod[7].subst;

    var fc = this.fontcolor, kc = this.keycolor, ikc = this.ikc;
    var ibc = this.ibc, bc = this.bordercolor, lic = this.lic;

    var arr_type = this.AltGr ? (this.Shift ? "alt_gr_shift" : "alt_gr") : (this.Shift ? "shift" : (this.Caps ? "caps" : "normal"));

    var nkeys = this.keys.length;
    var proto = VKeyboard.prototype;

    var norm_arr  = proto[layout + "_normal"];
    var caps_arr  = proto[layout + "_caps"];
    var shift_arr = proto[layout + "_shift"];
    var alt_arr   = proto[layout + "_alt_gr"];

    var alt_shift_arr = proto[layout + "_alt_gr_shift"];

    var dead_arr = proto[this.DeadAction[1]] || null;

    var bcaps  = (caps_arr  && (caps_arr.length  == nkeys));
    var bshift = (shift_arr && (shift_arr.length == nkeys));
    var balt   = (alt_arr   && (alt_arr.length   == nkeys));
    var baltsh = (balt      && alt_shift_arr && (alt_shift_arr.length == nkeys));
		
    var caps = this.mod[2], shift = this.mod[5], shift_r = this.mod[6], alt_gr = this.mod[11];
		
    if(bshift)
    {
      this._set_key_state(shift, true, fc, bc, this.Shift ? lic : kc);
      this._set_key_state(shift_r, true, fc, bc, this.Shift ? lic : kc);
    }
    else
    {
      this._set_key_state(shift, false, ibc, ibc, ikc);
      this._set_key_state(shift_r, false, ibc, ibc, ikc);

      if(arr_type == "shift")
      {
        arr_type = "normal";
        this.Shift = false;
      }
    }

    if(balt)
    {
      this._set_key_state(alt_gr, true, fc, bc, this.AltGr ? lic : kc);

      if(this.AltGr)
      {
        if(baltsh)
        {
          this._set_key_state(shift, true, fc, bc);
          this._set_key_state(shift_r, true, fc, bc);
        }
        else
        {
          this._set_key_state(shift, false, ibc, ibc, ikc);
          this._set_key_state(shift_r, false, ibc, ibc, ikc);

          arr_type = "alt_gr";
          this.Shift = false;
        }
      }
    }
    else
    {
      this._set_key_state(alt_gr, false, ibc, ibc, ikc);

      if(arr_type == "alt_gr")
      {
        arr_type = "normal";
        this.AltGr = false;
      }
      else if(arr_type == "alt_gr_shift")
      {
        arr_type = "normal";
        this.AltGr = false, this.Shift = false;

        shift.style.backgroundColor = kc, shift_r.style.backgroundColor = kc;
      }
    }

    if(this.Shift && !baltsh)
      this._set_key_state(alt_gr, false, ibc, ibc, ikc);
      
    if(bcaps && !this.AltGr)
      this._set_key_state(caps, true, fc, bc, this.Caps ? lic : kc);
    else
    {
      this._set_key_state(caps, false, ibc, ibc, ikc);

      this.Caps = false;
      if(arr_type == "caps") arr_type = "normal";
    }

    var arr_cur = proto[layout + "_" + arr_type];

    var i = nkeys;
    while(--i >= 0)
    {
      var key = this.keys[i], key_val = arr_cur[i]; if(!key_val) key_val = "";
			
      if(this.Shift && this.Caps)
      {
        var key_nrm = norm_arr[i], key_cps = caps_arr[i], key_shf = shift_arr[i];

        if((key_cps == key_shf) && (key_nrm != key_cps)) key_val = key_nrm;
      }

      if(typeof(key_val) == "object")
      {
        key.innerHTML = key_val[0], key.dead = key_val[1];

        this._set_key_state(key, true, this.deadcolor, bc, (this.DeadAction[0] == key_val[0] ? lic : kc));
      }
      else
      {
        key.dead = null;

        var block = false;

        if(key_val != "")
        {
          if(dead_arr)
          {
            for(var j = 0, l = dead_arr.length; j < l; j++) { var dk = dead_arr[j]; if(dk[0] == key_val) { key_val = dk[1]; break;}};

            if(j == l) block = true;
          }

          key.innerHTML = key_val;

          if(block)
            this._set_key_state(key, false, ibc, ibc, ikc);
          else
            this._set_key_state(key, true, fc, bc, kc);
        }
        else
        {
          key.innerHTML = "&nbsp;";
          this._set_key_state(key, false, ibc, ibc, ikc);
        }
      }
    }

    i = this.mod.length;
    while(--i >= 0)
    {
      var key = this.mod[i];
			
      switch(i)
      {
        case 1: case 2: case 5: case 6: case 11: case 4:
          break;

        case 7:
          break;
          key.innerHTML = layout;
          this._detach_event(key, 'mousedown', this._handle_lang_menu);
          if(this.DeadAction[1])
            this._set_key_state(key, false, ibc, ibc, ikc);
          else
          {
            var many = (this.avail_langs.length > 1);

            this._set_key_state(key, false, fc, many ? this.lsc : ibc, many ? kc : ikc);
            if(many)
              this._setup_event(key, 'mousedown', this._handle_lang_menu);
          }
          break;

        case 10:
          key.innerHTML = this.DeadAction[1] ? this.DeadAction[0] : "&nbsp;";

        default:
          if((this.DeadAction[1] && (i != 10)) || ((i == 8) || (i == 9) || (i == 12) || (i ==13)))
            this._set_key_state(key, false, ibc, ibc, ikc);
          else
            this._set_key_state(key, true, fc, bc, kc);

          var ks = key.style;
          switch(i)
          {
            case 4: ks.borderBottomColor = kc; break;

            case 8: case 9: case 12: case 13: ks.borderColor = ibc; break;
          }
      }
    }

    i = this.pad.length;
    while(--i >= 0)
    {
      key = this.pad[i];
      if(this.DeadAction[1])
        this._set_key_state(key, false, ibc, ibc, ikc);
      else
        this._set_key_state(key, true, fc, bc, kc);
    }
  },

  _handle_lang_menu: function(event)
  {
    var pr = VKeyboard.prototype;

    var in_el = pr._get_event_source(event);
    var container_id = in_el.id.substring(0, in_el.id.indexOf("___"));
    var vkboard = pr.kbArray[container_id];

    var ct = vkboard.Cntr, menu = vkboard.menu;

    if(menu)
    { ct.removeChild(menu); vkboard.menu = null; }
    else
    {
      var fs = vkboard.fontsize, kc = vkboard.keycolor, bc = "1px solid " + vkboard.bordercolor;
      var mag = parseFloat(fs) / 14.0, cell = Math.floor(25.0 * mag), cp = cell + "px", lh = (cell - 2) + "px", w = String(102 * mag) + "px";
      var h1 = Math.floor(cell + mag), h2 = String(100 * mag) + "px", pad = String(4 * mag) + "px";

      var langs = pr.avail_langs.length;

      menu = document.createElement("DIV"); var ms = menu.style;
      ms.display  = "block";
      ms.position = "relative";

      ms.top = "1px", ms.left = "0px";
      ms.width = w;
      ms.border = bc;
      ms.backgroundColor = vkboard.bgcolor;

      vkboard.menu = ct.appendChild(menu);

      var menu_main = document.createElement("DIV"); ms = menu_main.style;
      ms.fontFamily = vkboard.fontname;
      ms.position   = "relative";

      ms.color  = vkboard.fontcolor;
      ms.width  = w;
      ms.height = String(langs * h1 + 1) + "px";
      ms.cursor = "default";

      menu.appendChild(menu_main);

      function setcolor(obj, c) { return function() { obj.style.backgroundColor = c; } };

      for(var j = 0; j < langs; j++)
      {
        var item = vkboard._setup_key(menu_main, "___lang_" + String(j), String(h1 * j + 1) + "px", "1px", h2, cp, "left", lh, fs, "normal", pad);
        item.style.backgroundColor = kc;
        item.style.border = bc;
        item.innerHTML = pr.avail_langs[j][1];

        vkboard._setup_event(item, 'mousedown', vkboard._handle_lang_item);
        vkboard._setup_event(item, 'mouseover', setcolor(item, vkboard.lic));
        vkboard._setup_event(item, 'mouseout',  setcolor(item, kc));
      }
    }
  },

  _handle_lang_item: function(event)
  {
    var pr = VKeyboard.prototype;

    var in_el = pr._get_event_source(event);
    var container_id = in_el.id.substring(0, in_el.id.indexOf("___"));
    var vkboard = pr.kbArray[container_id];

    var ndx = in_el.id.indexOf("___lang_");
    var lng = in_el.id.substring(ndx + 8, in_el.id.length);
    var newl = pr.avail_langs[lng][0];

    if(vkboard.mod[7].innerHTML != newl)
      vkboard._refresh_layout(newl);

    vkboard.Cntr.removeChild(vkboard.menu);
    vkboard.menu = null;
  },
  _generic_callback_proc_mouseup: function(event)
  {
  	var pr = VKeyboard.prototype;
  	var in_el = pr._get_event_source(event);
	if(in_el.style.backgroundImage.indexOf('_down')>0){
  		in_el.style.backgroundImage=in_el.style.backgroundImage.replace('_down.gif','.gif');
	}
  },
  _generic_callback_proc_mouseout: function(event)
  {
  	var pr = VKeyboard.prototype;
  	var in_el = pr._get_event_source(event);
	if(in_el.style.backgroundImage.indexOf('_down')>0){
  		in_el.style.backgroundImage=in_el.style.backgroundImage.replace('_down.gif','.gif');
	}
  	in_el.style.border="0px solid";
  },
  _generic_callback_proc_mouseover: function(event)
  {
  	var pr = VKeyboard.prototype;
  	var in_el = pr._get_event_source(event);
  	in_el.style.border="1px solid #FCBE2C";
  },
  _generic_callback_proc: function(event)
  {
    var pr = VKeyboard.prototype;
    var in_el = pr._get_event_source(event);
    var container_id = in_el.id.substring(0, in_el.id.indexOf("___"));
    var vkboard = pr.kbArray[container_id];

    var val = in_el.subst || in_el.innerHTML;
    if(!val) return;
    switch(val)
    {
      case "Caps": case "Shift": case "AltGr":
				
        vkboard[val] = !vkboard[val];
        vkboard._refresh_layout();
        if(vkboard.sc) vkboard._start_flash(in_el);
        return;
			case "none":   val = "";   break;
      case "Tab":    val = "\t"; break;
      case "&nbsp;": val = " ";  break;
      case "&quot;": val = "\""; break;
      case "&lt;":   val = "<";  break;
      case "&gt;":   val = ">";  break;
      case "&amp;":  val = "&";  break;
    }

    if(vkboard.sc) vkboard._start_flash(in_el);
    
    if(in_el.dead)
    {
      if(in_el.dead == vkboard.DeadAction[1])
      { val = ""; vkboard.DeadAction[0] = vkboard.DeadAction[1] = null; }
      else
      { vkboard.DeadAction[0] = val; vkboard.DeadAction[1] = in_el.dead; }

      vkboard._refresh_layout();
      return;
    }
    else
    { var r;
      if(vkboard.DeadAction[1]) { vkboard.DeadAction[0] = vkboard.DeadAction[1] = null; r = true; }

      if(vkboard.AltGr || vkboard.Shift || r)
      {
        vkboard.AltGr = false; vkboard.Shift = false;
        vkboard._refresh_layout();
      }
    }

    if(vkboard._Callback) vkboard._Callback(val, vkboard.Cntr.id);
  },

  SetParameters: function()
  {
    var l = arguments.length;
    if(!l || (l % 2 != 0)) return false;

    var p0, p1, p2, p3, p4, p5, p6, p7, p8, p9, p10, p11, p12, p13, p14, p15;

    while(--l > 0)
    {
      var value = arguments[l];

      switch(arguments[l - 1])
      {
        case "callback":
          p0 = ((typeof(value) == "function") && ((value.length == 1) || (value.length == 2))) ? value : this._Callback;
          break;

        case "font-name":  p1 = value; break;
        case "font-size":  p2 = value; break;
        case "font-color": p3 = value; break;
        case "dead-color": p4 = value; break;
        case "base-color": p5 = value; break;
        case "key-color":  p6 = value; break;

        case "selection-color": p7 = value; break;
        case "border-color":    p8 = value; break;

        case "inactive-border-color": p9  = value; break;
        case "inactive-key-color":    p10 = value; break;
        case "lang-cell-color":       p11 = value; break;

        case "show-click": p12 = value; break;

        case "click-font-color":   p13  = value; break;
        case "click-key-color":    p14 = value; break;
        case "click-border-color": p15 = value; break;

        default: break;
      }

      l -= 1;
    }

    this._construct(this.Cntr.id, p0, (this.pad.length != 0), p1, p2, p3, p4, p5, p6, p7, p8, p9, p10, p11, p12, p13, p14, p15);

    return true;
  },
	showStatus : false,
  Show: function(value)
  {
    var ct = this.Cntr.style;
		this.showStatus = value;
    ct.display = ((value == undefined) || (value == true)) ? "block" : ((value == false) ? "none" : ct.display);
  },


  // Layout info:

  avail_langs: [["Us", "English (US)"], ["Ca", "Canadian"],
                ["Ru", "&#x0420;&#x0443;&#x0441;&#x0441;&#x043A;&#x0438;&#x0439;"], ["De", "Deutsch"],
                ["Fr", "Fran&#x00E7;ais"], ["Es", "Espa&#x00F1;ol"], ["It", "Italiano"], ["Cz", "&#x010C;esky"],
                ["El", "&#x0388;&#x03BB;&#x03BB;&#x03B7;&#x03BD;&#x03B1;&#x03C2;"], ["He", "&#x05E2;&#x05D1;&#x05E8;&#x05D9;&#x05EA;"]],

  // Us International:
  Us_normal: ["", "&#x0031;", "&#x0032;", "&#x0033;", "&#x0034;", "&#x0035;", "&#x0036;", "&#x0037;", "&#x0038;", "&#x0039;", "&#x0030;", "_", "",
              "&#x0071;", "&#x0077;", "&#x0065;", "&#x0072;", "&#x0074;", "&#x0079;", "&#x0075;", "&#x0069;", "&#x006F;", "&#x0070;", "", "", "",
              "&#x0061;", "&#x0073;", "&#x0064;", "&#x0066;", "&#x0067;", "&#x0068;", "&#x006A;", "&#x006B;", "&#x006C;", "", "",
              "&#x007A;", "&#x0078;", "&#x0063;", "&#x0076;", "&#x0062;", "&#x006E;", "&#x006D;", "","",""],

  Us_caps: ["", "&#x0031;", "&#x0032;", "&#x0033;", "&#x0034;", "&#x0035;", "&#x0036;", "&#x0037;", "&#x0038;", "&#x0039;", "&#x0030;", "_", "",
            "&#x0051;", "&#x0057;", "&#x0045;", "&#x0052;", "&#x0054;", "&#x0059;", "&#x0055;", "&#x0049;", "&#x004F;", "&#x0050;", "", "", "",
            "&#x0041;", "&#x0053;", "&#x0044;", "&#x0046;", "&#x0047;", "&#x0048;", "&#x004A;", "&#x004B;", "&#x004C;", "", "",
            "&#x005A;", "&#x0058;", "&#x0043;", "&#x0056;", "&#x0042;", "&#x004E;", "&#x004D;", "","",""],

  Us_shift: [["&#x007E;", "Tilde"], "&#x0021;", "&#x0040;", "&#x0023;", "&#x0024;", "&#x0025;", ["&#x005E;", "Circumflex"], "&#x0026;", "&#x002A;", "&#x0028;", "&#x0029;", "&#x005F;", "&#x002B;",
             "&#x0051;", "&#x0057;", "&#x0045;", "&#x0052;", "&#x0054;", "&#x0059;", "&#x0055;", "&#x0049;", "&#x004F;", "&#x0050;", "&#x007B;", "&#x007D;", "&#x007C;",
             "&#x0041;", "&#x0053;", "&#x0044;", "&#x0046;", "&#x0047;", "&#x0048;", "&#x004A;", "&#x004B;", "&#x004C;", "&#x003A;", "&#x0022;",,
             "&#x005A;", "&#x0058;", "&#x0043;", "&#x0056;", "&#x0042;", "&#x004E;", "&#x004D;", "&#x003C;", "&#x003E;", "&#x003F;"],

  Us_alt_gr: [, "&#x00A1;", "&#x00B2;", "&#x00B3;", "&#x00A4;", "&#x20AC;", "&#x00BC;", "&#x00BD;", "&#x00BE;", "&#x0091;", "&#x0092;", "&#x00A5;", "&#x00D7;",
              "&#x00E4;", "&#x00E5;", "&#x00E9;", "&#x00AE;", "&#x00FE;", "&#x00FC;", "&#x00FA;", "&#x00ED;", "&#x00F3;", "&#x00F6;", "&#x00AB;", "&#x00BB;",
              "&#x00AC;", "&#x00E1;", "&#x00DF;", "&#x0111;",,,,,, "&#x00F8;", "&#x00B6;", ["&#x00B4;", "Acute"],, "&#x00E6;",, "&#x00A9;",,,
              "&#x00F1;", "&#x00B5;", "&#x00E7;",, "&#x00BF;"],

  Us_alt_gr_shift: [, "&#x00B9;",,, "&#x00A3;",,,,,,,, "&#x00F7;", "&#x00C4;", "&#x00C5;", "&#x00C9;",, "&#x00DE;", "&#x00DC;",
                    "&#x00DA;", "&#x00CD;", "&#x00D3;", "&#x00D6;",,, "&#x00A6;", "&#x00C1;", "&#x00A7;", "&#x0110;",,,,,, "&#x00D8;",
                    "&#x00B0;", ["&#x00A8;", "Umlaut"],, "&#x00C6;",, "&#x00A2;",,, "&#x00D1;",, "&#x00C7;",,""]
};


function createKeyboard(srcId, targetId, bindId){
	var vbk = null;
	var keyb_callback = function(ch)
  {
     var text = document.getElementById(targetId), val = text.value;

     switch(ch)
     {
       case "BackSpace":
         var min = (val.charCodeAt(val.length - 1) == 10) ? 2 : 1;
         text.value = val.substr(0, val.length - min);
         break;

       case "Enter":
       	 this.Show(false);
         //text.value += "\n";
         break;

       default:
         text.value += ch;
     }
     text.focus();
  }
  
	vkb = new VKeyboard(srcId,    // container's id
                           keyb_callback, // reference to the callback function
                           true,          // create the numpad or not? (this and the following params are optional)
                           "",            // font name ("" == system default)
                           "15.7px",        // font size in px
                           "#000",        // font color
                           "#F00",        // font color for the dead keys
                           "#FFF",        // keyboard base background color
                           "#FFF",        // keys' background color
                           "#DDD",        // background color of switched/selected item
                           "#777",        // border color
                           "#CCC",        // border/font color of "inactive" key (key with no value/disabled)
                           "#FFF",        // background color of "inactive" key (key with no value/disabled)
                           "#F77",        // border color of the language selector's cell
                           true,          // show key flash on click? (false by default)
                           "#CC3300",     // font color during flash
                           "#FF9966",     // key background color during flash
                           "#CC3300",     // key border color during flash
                           false);        // embed VKeyboard into the page?                       
  vkb.Show(false);
  if(bindId){
  	var bindEle = document.getElementById(bindId);
  	bindEle.onclick=function(){
  		vkb.Show(!vkb.showStatus);
  	}
  }
  return vkb;
}