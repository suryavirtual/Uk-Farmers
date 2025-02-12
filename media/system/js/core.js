Joomla = window.Joomla || {}, Joomla.editors = Joomla.editors || {}, Joomla.editors.instances = Joomla.editors.instances || {},
    function(e, t) {
        "use strict";
        e.submitform = function(e, n, r) {
            n || (n = t.getElementById("adminForm")), e && (n.task.value = e), n.noValidate = !r;
            var i = t.createElement("input");
            i.style.display = "none", i.type = "submit", n.appendChild(i).click(), n.removeChild(i)
        }, e.submitbutton = function(t) {
            e.submitform(t)
        }, e.JText = {
            strings: {},
            _: function(e, t) {
                return typeof this.strings[e.toUpperCase()] != "undefined" ? this.strings[e.toUpperCase()] : t
            },
            load: function(e) {
                for (var t in e) {
                    if (!e.hasOwnProperty(t)) continue;
                    this.strings[t.toUpperCase()] = e[t]
                }
                return this
            }
        }, e.replaceTokens = function(e) {
            if (!/^[0-9A-F]{32}$/i.test(e)) return;
            var n = t.getElementsByTagName("input"),
                r, i, s;
            for (r = 0, s = n.length; r < s; r++) i = n[r], i.type == "hidden" && i.value == "1" && i.name.length == 32 && (i.name = e)
        }, e.isEmail = function(e) {
            var t = /^[\w.!#$%&‚Äô*+\/=?^`{|}~-]+@[a-z0-9-]+(?:\.[a-z0-9-]{2,})+$/i;
            return t.test(e)
        }, e.checkAll = function(e, t) {
            if (!e.form) return !1;
            t = t ? t : "cb";
            var n = 0,
                r, i, s;
            for (r = 0, s = e.form.elements.length; r < s; r++) i = e.form.elements[r], i.type == e.type && i.id.indexOf(t) === 0 && (i.checked = e.checked, n += i.checked ? 1 : 0);
            return e.form.boxchecked && (e.form.boxchecked.value = n), !0
        }, e.renderMessages = function(n) {
            e.removeMessages();
            var r = t.getElementById("system-message-container"),
                i, s, o, u, a, f, l;
            for (i in n) {
                if (!n.hasOwnProperty(i)) continue;
                s = n[i], o = t.createElement("div"), o.className = "alert alert-" + i, u = e.JText._(i), typeof u != "undefined" && (a = t.createElement("h4"), a.className = "alert-heading", a.innerHTML = e.JText._(i), o.appendChild(a));
                for (f = s.length - 1; f >= 0; f--) l = t.createElement("p"), l.innerHTML = s[f], o.appendChild(l);
                r.appendChild(o)
            }
        }, e.removeMessages = function() {
            var e = t.getElementById("system-message-container");
            while (e.firstChild) e.removeChild(e.firstChild);
            e.style.display = "none", e.offsetHeight, e.style.display = ""
        }, e.isChecked = function(e, n) {
            typeof n == "undefined" && (n = t.getElementById("adminForm")), n.boxchecked.value += e ? 1 : -1;
            if (!n.elements["checkall-toggle"]) return;
            var r = !0,
                i, s, o;
            for (i = 0, o = n.elements.length; i < o; i++) {
                s = n.elements[i];
                if (s.type == "checkbox" && s.name != "checkall-toggle" && !s.checked) {
                    r = !1;
                    break
                }
            }
            n.elements["checkall-toggle"].checked = r
        }, e.popupWindow = function(e, t, n, r, i) {
            var s = (screen.width - n) / 2,
                o = (screen.height - r) / 2,
                u = "height=" + r + ",width=" + n + ",top=" + o + ",left=" + s + ",scrollbars=" + i + ",resizable";
            window.open(e, t, u).window.focus()
        }, e.tableOrdering = function(n, r, i, s) {
            typeof s == "undefined" && (s = t.getElementById("adminForm")), s.filter_order.value = n, s.filter_order_Dir.value = r, e.submitform(i, s)
        }, window.writeDynaList = function(e, n, r, i, s) {
            var o = "<select " + e + ">",
                u = r == i,
                a = 0,
                f, l, c;
            for (l in n) {
                if (!n.hasOwnProperty(l)) continue;
                c = n[l];
                if (c[0] != r) continue;
                f = "";
                if (u && s == c[1] || !u && a === 0) f = 'selected="selected"';
                o += '<option value="' + c[1] + '" ' + f + ">" + c[2] + "</option>", a++
            }
            o += "</select>", t.writeln(o)
        }, window.changeDynaList = function(e, n, r, i, s) {
            var o = t.adminForm[e],
                u = r == i,
                a, f, l, c;
            while (o.firstChild) o.removeChild(o.firstChild);
            a = 0;
            for (f in n) {
                if (!n.hasOwnProperty(f)) continue;
                l = n[f];
                if (l[0] != r) continue;
                c = new Option, c.value = l[1], c.text = l[2];
                if (u && s == c.value || !u && a === 0) c.selected = !0;
                o.options[a++] = c
            }
            o.length = a
        }, window.radioGetCheckedValue = function(e) {
            if (!e) return "";
            var t = e.length,
                n;
            if (t === undefined) return e.checked ? e.value : "";
            for (n = 0; n < t; n++)
                if (e[n].checked) return e[n].value;
            return ""
        }, window.getSelectedValue = function(e, n) {
            var r = t[e][n],
                i = r.selectedIndex;
            return i !== null && i > -1 ? r.options[i].value : null
        }, window.listItemTask = function(e, n) {
        	document.getElementById(e).setAttribute("checked", true);
            var r = t.adminForm,
                i = 0,
                s, o = r[e];
            if (!o) return !1;
            for (;;) {
                s = r["cb" + i];
                if (!s) break;
                s.checked = !1, i++
            }
            return o.checked = !0, r.boxchecked.value = 1, window.submitform(n), !1
        }, window.submitbutton = function(t) {
            e.submitbutton(t)
        }, window.submitform = function(t) {
            e.submitform(t)
        }, window.saveorder = function(e, t) {
            window.checkAll_button(e, t)
        }, window.checkAll_button = function(n, r) {
            r = r ? r : "saveorder";
            var i, s;
            for (i = 0; i <= n; i++) {
                s = t.adminForm["cb" + i];
                if (!s) {
                    alert("You cannot change the order of items, as an item in the list is `Checked Out`");
                    return
                }
                s.checked = !0
            }
            e.submitform(r)
        }
    }(Joomla, document);