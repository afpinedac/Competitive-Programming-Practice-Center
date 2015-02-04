var avatarData = {};
var structData = {};

function parseXML(data) {

    structData.categories = new Array();
    $.each($(data).find("categories>*"), function(key, value) {
        var cat = {items: new Array(), name: value.tagName};

        cat.show = true;
        if (value.attributes.show) {
            cat.show = value.attributes.show.value != "0";
        }
        cat.colors = new Array();
        if (value.attributes.colors) {
            var acolors = value.attributes.colors.value.split(",");
            for (c in acolors) {
                cat.colors.push(acolors[c]);
            }
        }
        structData.categories.push(cat);
        $.each($(value).find("item"), function(k, v) {
            var o = new Object();
            $.each(v.attributes, function(i, attrib) {
                o[attrib.name] = attrib.value;
            });
            $.each($(v).children(), function(i, attrib) {
                o[attrib.tagName] = {cat: attrib.tagName};
                $.each(attrib.attributes, function(_i, _attrib) {
                    o[attrib.tagName][_attrib.name] = _attrib.value;
                });
            });

            if (o.empty != "1") {
                //colorized image
                o.src = link.avatarimages + "/" + value.tagName + "/" + o.id + ".png";
                o.image = $("<img src='" + o.src + "' />")[0];
                preloadImage(o.image);
                //over image
                if (o.o == "1") {
                    o.osrc = link.avatarimages + "/" + value.tagName + "/" + o.id + "_o.png";
                    o.oimage = $("<img src='" + o.osrc + "' />")[0];
                    preloadImage(o.oimage);
                }
                if (o.b == "1") {
                    o.bsrc = link.avatarimages + "/" + value.tagName + "/" + o.id + "_b.png";
                    o.bimage = $("<img src='" + o.bsrc + "' />")[0];
                    preloadImage(o.bimage);
                }
                if (o.pv == "1") {
                    o.pvsrc = link.avatarimages + "/" + value.tagName + "/" + o.id + "_p.png";
                    o.pvimage = $("<img src='" + o.pvsrc + "' />")[0];
                    preloadImage(o.pvimage);
                } else {
                    o.pvsrc = o.src;
                    o.pvimage = o.image;
                }
            }

            if (!(parseInt(avatarData.sex) & parseInt(o.s)))
                return;
            cat.items.push(o);
            o.category = cat;
        });
    });
 //   console.log(structData);
}

var waitingImages = false;
var imagesLoading = 0;
function preloadImage(image) {
    //if(!image)return;
    image = $(image);
    imagesLoading++;
    image.load(function() {
        imagesLoading--;
        if (imagesLoading == 0 && waitingImages) {
            onImagesLoaded();
        }
    });
}

var currentxml;
function onImagesLoaded() {
    if (imagesLoading == 0) {
        parseAvatar(currentxml);
        currentxml = null;
        buildCategoriesPanel();
    } else {
        waitingImages = true;
    }
}

function parseAvatar(data) {
    avatarData.current = {};
    setItem(getItem("background", data.background));
    setItem(getItem("body", data.body));
    setItem(getItem("clothes", data.clothes));
    setItem(getItem("head", data.head));
    setItem(getItem("eyes", data.eyes));
    setItem(getItem("nose", data.nose));
    setItem(getItem("mouth", data.mouth));
    setItem(getItem("hair", data.hair));
    setItemColor(avatarData.current.head, data.headColor);
    setItemColor(avatarData.current.clothes, data.clothesColor);
    setItemColor(avatarData.current.eyes, data.eyesColor);
    setItemColor(avatarData.current.hair, data.hairColor);
}

function setup() {
    $.post(link.avatar, {task: 'getUserData'}, function(adata) {
    //   window.console.log(link);      
      //  window.console.log(adata);

        adata = JSON.parse(adata);
       
        avatarData.sex = adata.sex;
        avatarData.unlocked = adata.unlocked;
        $.get(link.avatarxml, function(data) {
           // window.console.log(data);
            parseXML(data);
            currentxml = adata.current;
            onImagesLoaded();
        });
    });

    $("#back_btn").click(showCategoriesPanel);
    $("#save_btn").click(save);
}

function save() {
    var sendingData = {
        background: avatarData.current.background.id,
        body: avatarData.current.body.id,
        clothes: avatarData.current.clothes.id,
        head: avatarData.current.head.id,
        eyes: avatarData.current.eyes.id,
        nose: avatarData.current.nose.id,
        mouth: avatarData.current.mouth.id,
        hair: avatarData.current.hair.id,
        headColor: avatarData.current.headColor,
        clothesColor: avatarData.current.clothesColor,
        eyesColor: avatarData.current.eyesColor,
        hairColor: avatarData.current.hairColor
    };
    sendingData = JSON.stringify(sendingData);
    $.post(link.avatar, {task: 'saveUserData', userdata: sendingData}, function(data) {
        //console.log("save result:\n" + data);
        
        alertify.log("EL AVATAR HA SIDO GUARDADO CORRECTAMENTE", "success",2000);
    });
}

function buildCategoriesPanel() {
    var cont = $("#categories").html("");
    for (cat in structData.categories) {
        if (!structData.categories[cat].show)
            continue;
        var btn = $("<div class='category_btn' cati='" + cat + "' style=\"background-image:url('/cpp/public/avatares/images/icons/" + structData.categories[cat].name + ".png')\"></div>");
        cont.append(btn);
        btn.click(function() {
            showItemsPanel(structData.categories[$(this).attr("cati")]);
        });
    }
    cont.fadeIn();
    $("#save_btn").fadeIn();
}

var currentCategory;
function showItemsPanel(category) {
    if (category == currentCategory)
        return;
    currentCategory = category;
    var cont = $("#items").html("");
    cont.attr("category", category.name);
    for (i in category.items) {
        if (category.items[i].pvsrc) {
            var btn = $("<div class='item_btn' itemi='" + i + "' style=\"background-image:url('" + category.items[i].pvsrc + "')\"></div>");
        } else {
            btn = $("<div class='item_btn' itemi='" + i + "'></div>");
        }
        if (avatarData.current[category.name] === category.items[i]) {
            btn.attr("selected", "");
            showColorsPanel(category.items[i]);
        }
        cont.append(btn);
        btn.click(function() {
            $(".item_btn[selected]").removeAttr("selected");
            $(this).attr("selected", "");
            var item = currentCategory.items[$(this).attr("itemi")];
            setItem(item);
            showColorsPanel(item);
        });
    }
    $("#categories").fadeOut(function() {
        cont.fadeIn();
        $("#panel_buttons").fadeIn();
        //$("#panel_colors").fadeIn();
    });
}

function showCategoriesPanel() {
    var cont = $("#items");
    $("#panel_buttons").fadeOut();
    //$("#panel_colors").fadeOut();
    cont.fadeOut(function() {
        $("#categories").fadeIn();
        cont.html("");
        currentCategory = null;
    });
}

function showColorsPanel(item) {
    var colors = item.category.colors;
    var cont = $("<div></div>");

    for (c in colors) {
        var btn = $("<div class='color_btn' style='background-color:#" + colors[c] + "' color='" + colors[c] + "'></div>");
        cont.append(btn);
        btn.click(function() {
            setItemColor(item, $(this).attr("color"));
        });
    }
    cont.css("width", (parseInt(c) + 1) * 35);

    $("#panel_colors").html('').append(cont);
}

function setItemColor(item, color) {
    avatarData.current[item.category.name + "Color"] = color;
    if (item.category.name == "head") {
        avatarData.current["bodyColor"] = avatarData.current["mouthColor"] = avatarData.current["noseColor"] = color;
    }
    redraw();
}

function setItem(item) {
    avatarData.current[item.category.name] = item;
    redraw();
}

function getItem(category, id) {
    for (cat in structData.categories) {
        cat = structData.categories[cat];
        if (cat.name == category) {
            for (i in cat.items) {
                if (cat.items[i].id == id)
                    return cat.items[i];
            }
            break;
        }
    }
    return null;
}

function redraw() {
    var canvas = $("#avatar_canvas")[0];
    var context = canvas.getContext("2d");
    context.clearRect(0, 0, canvas.width, canvas.height);
    var postImages = new Array();
    for (cat in structData.categories) {
        var cat = structData.categories[cat];
        var item = avatarData.current[cat.name];
        if (item && item.image) {
            var obj = getItemImageData(item, avatarData.current[cat.name + "Color"]);
            if (obj.bimage) {
                context.drawImage(obj.bimage, obj.position.x, obj.position.y);
                postImages.push(obj);
            } else if (item.post == "1") {
                postImages.push(obj);
            } else {
                context.drawImage(obj.image, obj.position.x, obj.position.y);
            }
        }
    }
    for (obj in postImages) {
        obj = postImages[obj];
        context.drawImage(obj.image, obj.position.x, obj.position.y);
    }

    //aux
    /*context.moveTo(300,0);
     context.lineTo(300,600);
     context.stroke();
     context.moveTo(0,300);
     context.lineTo(600,300);
     context.moveTo(0,350);
     context.lineTo(600,350);
     context.moveTo(0,400);
     context.lineTo(600,400);
     context.stroke();*/
}

function getItemPosition(item) {
    var pos = {x: parseInt(item.x) || 0, y: parseInt(item.y) || 0, sx: parseFloat(item.sx) || 1, sy: parseFloat(item.sy) || 1};
    switch (item.category.name) {
        case "head":
            pos.x = 300 - (item.image.width / 2 - pos.x) * pos.sx;
            pos.y = 300 - (item.image.height / 2 - pos.y) * pos.sy;
            break;
        case "background":
            pos.x = 0;
            pos.y = 0;
            break;
        case "body":
        case "clothes":
            pos.x = 300 - (item.image.width / 2 - pos.x) * pos.sx;
            pos.y = 380;
            break;
        default:
            var props = avatarData.current.head[item.category.name];
            pos.sx *= parseFloat(props.sx) || 1;
            pos.sy *= parseFloat(props.sy) || 1;
            if (item.image) {
                pos.x = 300 + (parseInt(props.x) || 0) - (item.image.width / 2 - pos.x) * pos.sx;
                pos.y = 300 + (parseInt(props.y) || 0) - (item.image.height / 2 - pos.y) * pos.sy;
            }
    }
    if (item.image) {
        pos.width = pos.sx * item.image.width;
        pos.height = pos.sy * item.image.height;
    }
    return pos;
}

function getItemImageData(item, color) {
    if (!item.image)
        return;
    var obj = {};
    var pos = getItemPosition(item);
    var canvas = $("<canvas width='" + pos.width + "' height='" + pos.height + "' />");
    var context = canvas[0].getContext("2d");
    context.drawImage(item.image, 0, 0, pos.width, pos.height);
    var imageData = context.getImageData(0, 0, pos.width, pos.height);
    color = hexToRgb(color);
    if (color) {
        for (var i = 0; i < imageData.data.length; i += 4) {
            imageData.data[i + 0] *= color.r;
            imageData.data[i + 1] *= color.g;
            imageData.data[i + 2] *= color.b;
        }
        context.putImageData(imageData, 0, 0);
    }
    if (item.oimage) {
        context.drawImage(item.oimage, 0, 0, pos.width, pos.height);
    }

    if (item.bimage) {
        var canvasb = $("<canvas width='" + pos.width + "' height='" + pos.height + "' />");
        var contextb = canvasb[0].getContext("2d");
        contextb.drawImage(item.bimage, 0, 0, pos.width, pos.height);
        var imageDatab = contextb.getImageData(0, 0, pos.width, pos.height);
        if (color) {
            for (var i = 0; i < imageDatab.data.length; i += 4) {
                imageDatab.data[i + 0] *= color.r;
                imageDatab.data[i + 1] *= color.g;
                imageDatab.data[i + 2] *= color.b;
            }
            contextb.putImageData(imageDatab, 0, 0);
        }
        obj.bimage = canvasb[0];
    }

    obj.position = pos;
    obj.image = canvas[0];
    return obj;
}

function hexToRgb(hex) {
    if (!hex)
        return null;
    var shorthandRegex = /^#?([a-f\d])([a-f\d])([a-f\d])$/i;
    hex = hex.replace(shorthandRegex, function(m, r, g, b) {
        return r + r + g + g + b + b;
    });

    var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
    return result ? {
        r: parseInt(result[1], 16) / 255,
        g: parseInt(result[2], 16) / 255,
        b: parseInt(result[3], 16) / 255
    } : null;
}


setup();