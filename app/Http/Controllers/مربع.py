import pyautogui as gui
import time

time.sleep(5)

l=[]
m=[]
for i in range(0,50):
    gui.click(i+100,400)
for i in range(400,450):
    gui.click(100,i)
for i in range(400,450):
    gui.click(149,i)
for i in range(0,50):
    gui.click(i+100,450)

print('l:',l)
print('m:',m)
