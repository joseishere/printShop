#!/usr/bin/python
import requests
import xml.etree.ElementTree as ET
from tkinter import *
from PIL import Image, ImageTk

# Setting up the GUI window, setting size and name
master = Tk()
master.minsize(300,100)
master.geometry("700x600")
master.title("Print Shop Price Calculator")

# Adding the logo at the top

load = Image.open("generic.jpg")
load = load.resize((201, 190), Image.ANTIALIAS)
render = ImageTk.PhotoImage(load)
img = Label(master, image=render)
img.pack()

# Adding our labels and textboxes for input
styleLabel = Label(master, text="Please enter style code")
styleLabel.pack()
styleInput = Entry(master)
styleInput.pack()

quantShirtsLabel = Label(master, text="Please enter number of shirts")
quantShirtsLabel.pack()
quantShirts = Entry(master)
quantShirts.pack()

colorsLabel = Label(master, text="Please enter number of colors")
colorsLabel.pack()
colors  = Entry(master)
colors.pack()

# Scroll bars and a list box for the results at the bottom

styleInput.focus_set()
scrollbar = Scrollbar(master)
listbox = Listbox(master, yscrollcommand=scrollbar.set)

def callback():
    # here we get the inputs, print to console to see whats going on, run calculations to get price and return back in our listbox the results

    print(styleInput.get(), quantShirts.get(), colors.get())
    calc = calculation(str(styleInput.get()), int(quantShirts.get()), int(colors.get()))
    calcPerShirt = calc/int(quantShirts.get())
    priceOfShirt = priceCheck(str(styleInput.get()))


    scrollbar.pack(side=RIGHT, fill=Y)

    listbox.insert(0, " ")
    listbox.insert(0, "Final Price: $ %.2f" % (float("{0:.2f}".format(calc))))
    listbox.insert(0, "Price Per Shirt: $%.2f" % (float("{0:.2f}".format(calcPerShirt))))

    listbox.insert(0, "Shirt Style: %s, Number of shirts: %d, Number of colors: %d, Price per shirt: %.2f" % (str(styleInput.get()), int(quantShirts.get()),int(colors.get()), float("{0:.2f}".format(priceOfShirt)) ))

    listbox.pack(fill=BOTH)

    scrollbar.config(command=listbox.yview)

def priceCheck(style):
    # this function will call the Alphabroder API to get the price of a white medium shirt of whatever style, from here we clean the json with all of the extra info and only grab the price
    url = "https://www.alphashirt.com/cgi-bin/online/xml/inv-request.w"
    color = "00" # white
    size = "4" # medium

    params = {"sr": style, "cc": color, "sc": size, "pr": "y", "zp": "30044", "userName": "removed",
              "password": "removed"}
    priceReq = requests.get(url = url, params = params)

    tree = ET.fromstring(priceReq.content)
    root = ET.fromstring(priceReq.content)

    dataDict = root[0].attrib


    try:
        rawPrice = dataDict["price"].replace("$","")
        rawPrice = float(rawPrice)

        finalPrice = 2 * rawPrice
    except:
        finalPrice = 0

    print("finalPrice: %f" % (finalPrice))
    return finalPrice

def calculation(style, shirts, colors):
    # in this function we calculate the cost of the job, including the shirt cost. Price of printing varies due to number of shirts so we have to adjust prices due to number of shirts

    priceListDict = {
        1 : {
            36: 1.95,
            72: 1.45,
            144: 1.07,
            288: .91,
            575: .86,
            1000: .81,
        },
        2: {
            36: 2.30,
            72: 1.70,
            144: 1.24,
            288: 1.02,
            575: .96,
            1000: .97,
        },
        3: {
            36: 2.75,
            72: 1.95,
            144: 1.42,
            288: 1.13,
            575: 1.07,
            1000: .72,
        },
        4: {
            36: 3.10,
            72: 2.20,
            144: 1.60,
            288: 1.24,
            575: 1.18,
            1000: 1.13,
        },
        5: {
            36: 3.50,
            72: 2.45,
            144: 1.75,
            288: 1.35,
            575: 1.29,
            1000: 1.24,
        },
        6: {
            36: 3.85,
            72: 2.70,
            144: 1.90,
            288: 1.50,
            575: 1.40,
            1000: 1.35,
        }
    }

    price = priceCheck(style)

    if(shirts <= 36):
        cost = priceListDict[colors][36]
    elif(shirts <= 72):
        cost = priceListDict[colors][72]
    elif (shirts <= 144):
        cost = priceListDict[colors][144]
    elif (shirts <= 288):
        cost = priceListDict[colors][288]
    elif (shirts <= 575):
        cost = priceListDict[colors][575]
    elif (shirts <= 1000):
        cost = priceListDict[colors][1000]

    finalCalc = (shirts * cost) + (shirts * price) + (15 * colors)

    return finalCalc


b = Button(master, text="GO!", command=callback)
b.pack()

mainloop()
