from experta import *
from flask import Flask, render_template, request

eng = KnowledgeEngine()
app = Flask(__name__)

ANswer = ["y", "n"]
Yes = "y"
No = "n"


class CarBreakdownSymptoms(KnowledgeEngine):
    @app.route("/", methods=["GET"])
    def index():
        # print(" is the engine check light on ? ")
        return render_template(
            "Questions.html", Question="is the engine check light on ?", inc="/f0"
        )

    @app.route("/f0", methods=["POST"])
    def f0():
        #  ans = input("is the engine check light on ?")

        ans = request.form.get("YESNO")
        eng.declare(Fact(start=ans))
        if ans == "y":
            return render_template(
                "Questions.html", Question="is there any Loud noises ?", inc="/f1"
            )
        else:
            return render_template(
                "Questions.html", Question="is there any Misfire ?", inc="/f34"
            )

    ##################################################
    # The Tree Left Side
    ##################################################
    @app.route("/f1", methods=["POST"])
    def f1():
        ans = request.form.get("YESNO")
        eng.declare(Fact(LoudNoisee=ans))
        if ans == "y":
            return render_template(
                "Questions.html", Question="is there any Overheating ?", inc="/f2"
            )
        else:
            return render_template(
                "Questions.html", Question="is there any misfire ?", inc="/f24"
            )

    @app.route("/f2", methods=["POST"])
    def f2():
        ans = request.form.get("YESNO")
        eng.declare(Fact(OverHeating1=ans))

        if ans == "y":
            return render_template(
                "Questions.html", Question="is there any misfire ?", inc="/f3"
            )
        else:
            return render_template(
                "Questions.html",
                Question="is there any Difficulty i shifting the gear ?",
                inc="/f7",
            )

    @app.route("/f3", methods=["POST"])
    def f3():
        ans = request.form.get("YESNO")
        eng.declare(Fact(misfire1=ans))
        if ans == "y":
            return render_template(
                "Questions.html", Question="is there any Battery drain ?", inc="/f4"
            )
        else:
            return render_template(
                "Result.html",
                Results="Your problem contains overlapping faults and should be presented to a specialist directly in order to accurately describe your problem ",
            )

    @app.route("/f4", methods=["POST"])
    def f4():
        ans = request.form.get("YESNO")

        eng.declare(Fact(ProblemElectical1=ans))
        if ans == "y":
            return render_template(
                "Questions.html", Question="is there any Battery drain ?", inc="/f5"
            )
        else:
            return render_template(
                "Result.html",
                Results="Your problem contains overlapping faults and should be presented to a specialist directly in order to accurately describe your problem ",
            )

    @app.route("/f5", methods=["POST"])
    def f5():
        return render_template(
            "Result.html",
            Results="Your problem is might be in the electical system in your car ",
        )

    @app.route("/f7", methods=["POST"])
    @Rule(Fact(OverHeating1=No))
    def f7():
        ans = request.form.get("YESNO")

        eng.declare(Fact(DifShiftGear=ans))
        if ans == "y":
            return render_template(
                "Questions.html", Question="is there any Vibration ?", inc="/f8"
            )
        else:
            return render_template(
                "Questions.html", Question="is there any Engine Stalling ?", inc="/f17"
            )

    @app.route("/f8", methods=["POST"])
    @Rule(Fact(DifShiftGear=Yes))
    def f8():
        ans = request.form.get("YESNO")

        eng.declare(Fact(Vibration1=ans))
        if ans == "y":
            return render_template(
                "Questions.html",
                Question="is there any slipping in the clutch ?",
                inc="/f9",
            )
        else:
            return render_template(
                "Questions.html",
                Question="is there any slipping in the Gear ?",
                inc="/f12",
            )

    @app.route("/f9", methods=["POST"])
    @Rule(Fact(Vibration1=Yes))
    def f9():
        ans = request.form.get("YESNO")

        eng.declare(Fact(SlippingClutch=ans))
        if ans == "y":
            return render_template(
                "Result.html",
                Results="Your problem is might be in Clutch plate ?",
            )
        else:
            return render_template(
                "Result.html",
                Results="Your problem contains overlapping faults and should be presented to a specialist directly in order to accurately describe your problem ",
            )

    @app.route("/f12", methods=["POST"])
    @Rule(Fact(Vibration1=No))
    def f12():
        ans = request.form.get("YESNO")

        eng.declare(Fact(SlippingGear=ans))
        if ans == "y":
            return render_template(
                "Questions.html",
                Question="is there any leaking in transmission fluid ?",
                inc="/f13",
            )
        else:
            return render_template(
                "Result.html",
                Results="Your problem contains overlapping faults and should be presented to a specialist directly in order to accurately describe your problem ",
            )

    @app.route("/f13", methods=["POST"])
    @Rule(Fact(SlippingGear=Yes))
    def f13():
        ans = request.form.get("YESNO")

        eng.declare(Fact(LeakTransFluid=ans))
        if ans == "y":
            return render_template(
                "Result.html",
                Results="Your problem is might be in car gearbox ",
            )
        else:
            return render_template(
                "Result.html",
                Results="Your problem contains overlapping faults and should be presented to a specialist directly in order to accurately describe your problem ",
            )

    @app.route("/f17", methods=["POST"])
    @Rule(Fact(DifShiftGear=No))
    def f17():
        ans = request.form.get("YESNO")

        eng.declare(Fact(EngStalling1=ans))
        if ans == "y":
            return render_template(
                "Questions.html", Question="is there any low fuel pressure ?", inc="/f18"
            )
        else:
            return render_template(
                "Result.html",
                Results="Your problem contains overlapping faults and should be presented to a specialist directly in order to accurately describe your problem ",
            )

    @app.route("/f18", methods=["POST"])
    @Rule(Fact(EngStalling1=Yes))
    def f18():
        ans = request.form.get("YESNO")

        eng.declare(Fact(FuellowPressur=ans))
        if ans == "y":
            return render_template(
                "Questions.html",
                Question="is there any Fuel lake ?",
                inc="/f19",
            )
        else:
            return render_template(
                "Result.html",
                Results="Your problem contains overlapping faults and should be presented to a specialist directly in order to accurately describe your problem ",
            )

    @app.route("/f19", methods=["POST"])
    @Rule(Fact(FuellowPressur=Yes))
    def f19():
        ans = request.form.get("YESNO")

        eng.declare(Fact(FuelLake=ans))
        if ans == "y":
            return render_template(
                "Result.html",
                Results="Your problem is Might Be in casoline pump ",
            )
        else:
            return render_template(
                "Result.html",
                Results="Your problem contains overlapping faults and should be presented to a specialist directly in order to accurately describe your problem ",
            )

    @app.route("/f24", methods=["POST"])
    @Rule(Fact(LoudNoisee=No))
    def f24():
        ans = request.form.get("YESNO")

        eng.declare(Fact(misfire2=ans))
        if ans == "y":
            return render_template(
                "Questions.html",
                Question="is there any Reduce in fuel encome ?",
                inc="/f70",
            )
        else:
            return render_template(
                "Questions.html",
                Question="is there any Vibration ?",
                inc="/f25",
            )

    @app.route("/f25", methods=["POST"])
    @Rule(Fact(misfire2=Yes))
    def f25():
        ans = request.form.get("YESNO")

        eng.declare(Fact(ReduceFuelIncome=ans))
        if ans == "y":
            return render_template(
                "Questions.html",
                Question="is there any Engine Stalling ?",
                inc="/f26",
            )
        else:
            return render_template(
                "Result.html",
                Results="Your problem contains overlapping faults and should be presented to a specialist directly in order to accurately describe your problem ",
            )

    @app.route("/f26", methods=["POST"])
    @Rule(Fact(ReduceFuelIncome=Yes))
    def f26():
        ans = request.form.get("YESNO")

        eng.declare(Fact(EngStalling=ans))
        if ans == "y":
            return render_template(
                "Questions.html",
                Question="is there any increment in emission ?",
                inc="/f27",
            )
        else:
            return render_template(
                "Result.html",
                Results="Your problem contains overlapping faults and should be presented to a specialist directly in order to accurately describe your problem ",
            )

    @app.route("/f27", methods=["POST"])
    @Rule(Fact(EngStalling=Yes))
    def f27():
        ans = request.form.get("YESNO")

        eng.declare(Fact(EmissionInc=ans))
        if ans == "y":
            return render_template(
                "Questions.html",
                Question="Is It Normall ?",
                inc="/f28",
            )
        else:
            return render_template(
                "Result.html",
                Results="Your problem contains overlapping faults and should be presented to a specialist directly in order to accurately describe your problem ",
            )

    @app.route("/f28", methods=["POST"])
    @Rule(Fact(EmissionInc=Yes))
    def f28():
        ans = request.form.get("YESNO")

        eng.declare(Fact(EmissionIncNormall=ans))
        if ans == "y":
            return render_template(
                "Result.html",
                Results="your problem is might be in the fuel filter",
            )

        else:
            return render_template(
                "Result.html",
                Results="Your problem contains overlapping faults and should be presented to a specialist directly in order to accurately describe your problem ",
            )

    @app.route("/f70", methods=["POST"])
    @Rule(Fact(misfire2=No))
    def f70():
        ans = request.form.get("YESNO")

        eng.declare(Fact(Vibration2=ans))
        if ans == "y":
            return render_template(
                "Questions.html",
                Question="Did the distance indicator stop increasing ?",
                inc="/f71",
            )
        else:
            return render_template(
                "Questions.html",
                Question="is there any blown Fuse? ",
                inc="/f76",
            )

    @app.route("/f71", methods=["POST"])
    @Rule(Fact(Vibration2=Yes))
    def f71():
        ans = request.form.get("YESNO")

        eng.declare(Fact(IndectorStop=ans))
        if ans == "y":
            return render_template(
                "Questions.html",
                Question="Did the car sways or roll when cornering ?",
                inc="/f72",
            )
        else:
            return render_template(
                "Result.html",
                Results="Your problem contains overlapping faults and should be presented to a specialist directly in order to accurately describe your problem ",
            )

    @app.route("/f72", methods=["POST"])
    @Rule(Fact(IndectorStop=Yes))
    def f72():
        ans = request.form.get("YESNO")

        eng.declare(Fact(Sway=ans))
        if ans == "y":
            return render_template(
                "Questions.html",
                Question="is there any leaking fluid ?",
                inc="/f73",
            )
        else:
            return render_template(
                "Result.html",
                Results="Your problem contains overlapping faults and should be presented to a specialist directly in order to accurately describe your problem ",
            )

    @app.route("/f73", methods=["POST"])
    @Rule(Fact(Sway=Yes))
    def f73():
        ans = request.form.get("YESNO")

        eng.declare(Fact(fluidleak=ans))
        if ans == "y":
            return render_template(
                "Result.html",
                Results="Your Problem Is Might Be car shock absorber ",
            )
        else:
            return render_template(
                "Result.html",
                Results="Your problem contains overlapping faults and should be presented to a specialist directly in order to accurately describe your problem ",
            )

    @app.route("/f76", methods=["POST"])
    @Rule(Fact(Vibration2=No))
    def f76():
        ans = request.form.get("YESNO")

        eng.declare(Fact(BlownFuse=ans))
        if ans == "y":
            return render_template(
                "Questions.html",
                Question="is there any circuit does not work ?",
                inc="/f77",
            )
        else:
            return render_template(
                "Result.html",
                Results="Your problem contains overlapping faults and should be presented to a specialist directly in order to accurately describe your problem ",
            )

    @app.route("/f77", methods=["POST"])
    @Rule(Fact(BlownFuse=Yes))
    def f77():
        ans = request.form.get("YESNO")

        eng.declare(Fact(CircuitDoesNtWork=ans))
        if ans == "y":
            return render_template(
                "Result.html",
                Results="Your Problem Is Might Be In Fuse ",
            )
        else:
            return render_template(
                "Result.html",
                Results="Your problem contains overlapping faults and should be presented to a specialist directly in order to accurately describe your problem ",
            )

    ##################################################
    # The Tree Right Side
    ##################################################
    @app.route("/f34", methods=["POST"])
    @Rule(Fact(start=No))
    def f34():
        ans = request.form.get("YESNO")

        eng.declare(Fact(Misfire=ans))
        if ans == "y":
            return render_template(
                "Questions.html", Question="is there any overheating ?", inc="/f35"
            )
        else:
            return render_template(
                "Questions.html",
                Question="is the car won't start ?",
                inc="/f56",
            )

    @app.route("/f35", methods=["POST"])
    @Rule(Fact(Misfire=Yes))
    def f35():
        ans = request.form.get("YESNO")

        eng.declare(Fact(overheating=ans))
        if ans == "y":
            return render_template(
                "Questions.html",
                Question="is there any Loud noises ?",
                inc="/f36",
            )
        else:
            return render_template(
                "Questions.html",
                Question="is there any Oil Pressure Warning Light ?",
                inc="/f49",
            )

    @app.route("/f36", methods=["POST"])
    @Rule(Fact(overheating=Yes))
    def f36():
        ans = request.form.get("YESNO")

        eng.declare(Fact(LoudNoise=ans))
        if ans == "y":
            return render_template(
                "Questions.html", Question="is there any Battery drain ?", inc="/f37"
            )
        else:
            return render_template(
                "Questions.html",
                Question="is there any steam from the engine ?",
                inc="/f40",
            )

    @app.route("/f37", methods=["POST"])
    @Rule(Fact(LoudNoise=Yes))
    def f37():
        ans = request.form.get("YESNO")

        eng.declare(Fact(BatteryDrain=ans))
        if ans == "y":
            return render_template(
                "Result.html",
                Results="Your problem is might be in the electical system in your car ",
            )
        else:
            return render_template(
                "Result.html",
                Results="Your problem contains overlapping faults and should be presented to a specialist directly in order to accurately describe your problem ",
            )

    @app.route("/f40", methods=["POST"])
    @Rule(Fact(LoudNoise=No))
    def f40():
        ans = request.form.get("YESNO")

        eng.declare(Fact(engsteam=ans))
        if ans == "y":
            return render_template(
                "Questions.html",
                Question="is there any knocking or rattling from the engine ",
                inc="/f41",
            )
        else:
            return render_template(
                "Result.html",
                Results="Your problem contains overlapping faults and should be presented to a specialist directly in order to accurately describe your problem ",
            )

    @app.route("/f41", methods=["POST"])
    @Rule(Fact(engsteam=Yes))
    def f41():
        ans = request.form.get("YESNO")

        eng.declare(Fact(engknockAndratting=ans))
        if ans == "y":
            return render_template(
                "Questions.html",
                Question="is there any white smoke from the exausted ",
                inc="/f42",
            )
        else:
            return render_template(
                "Result.html",
                Results="Your problem contains overlapping faults and should be presented to a specialist directly in order to accurately describe your problem ",
            )

    @app.route("/f42", methods=["POST"])
    @Rule(Fact(engknockAndratting=Yes))
    def f42():
        ans = request.form.get("YESNO")

        eng.declare(Fact(exhauSteam=ans))
        if ans == "y":
            return render_template(
                "Questions.html",
                Question="is there any Loss of power ?",
                inc="/f43",
            )
        else:
            return render_template(
                "Result.html",
                Results="Your problem contains overlapping faults and should be presented to a specialist directly in order to accurately describe your problem ",
            )

    @app.route("/f43", methods=["POST"])
    @Rule(Fact(exhauSteam=Yes))
    def f43():
        ans = request.form.get("YESNO")

        eng.declare(Fact(PowerLoss=ans))
        if ans == "y":
            return render_template(
                "Result.html",
                Results="Your problem is might be the water of the car ",
            )
        else:
            return render_template(
                "Result.html",
                Results="Your problem contains overlapping faults and should be presented to a specialist directly in order to accurately describe your problem ",
            )

    @app.route("/f49", methods=["POST"])
    @Rule(Fact(overheating=No))
    def f49():
        ans = request.form.get("YESNO")

        eng.declare(Fact(OilLight=ans))
        if ans == "y":
            return render_template(
                "Questions.html",
                Question="is there any Smell of burning oil ?",
                inc="/f50",
            )
        else:
            return render_template(
                "Result.html",
                Results="Your problem contains overlapping faults and should be presented to a specialist directly in order to accurately describe your problem ",
            )

    @app.route("/f50", methods=["POST"])
    @Rule(Fact(OilLight=Yes))
    def f50():
        ans = request.form.get("YESNO")

        eng.declare(Fact(BurningOilSmell=ans))
        if ans == "y":
            return render_template(
                "Questions.html",
                Question="is there any Weakence in Performance ?",
                inc="/f51",
            )
        else:
            return render_template(
                "Result.html",
                Results="Your problem contains overlapping faults and should be presented to a specialist directly in order to accurately describe your problem ",
            )

    @app.route("/f51", methods=["POST"])
    @Rule(Fact(BurningOilSmell=Yes))
    def f51():
        ans = request.form.get("YESNO")

        eng.declare(Fact(BurningOilSmell=ans))
        if ans == "y":
            return render_template(
                "Result.html",
                Results="your problem is might be in the Oil ",
            )

        else:
            return render_template(
                "Result.html",
                Results="Your problem contains overlapping faults and should be presented to a specialist directly in order to accurately describe your problem ",
            )

    @app.route("/f56", methods=["POST"])
    @Rule(Fact(Misfire=No))  # Battery side
    def f56():
        ans = request.form.get("YESNO")

        eng.declare(Fact(CarNotStart=ans))
        if ans == "y":
            return render_template(
                "Questions.html",
                Question="is the car start slowly ?",
                inc="/f57",
            )

        else:
            return render_template(
                "Result.html",
                Results="Your problem contains overlapping faults and should be presented to a specialist directly in order to accurately describe your problem ",
            )

    @app.route("/f57", methods=["POST"])
    @Rule(Fact(CarNotStart=Yes))
    def f57():
        ans = request.form.get("YESNO")

        eng.declare(Fact(slowlystart=ans))
        if ans == "y":
            return render_template(
                "Questions.html",
                Question="is the car won't start ?",
                inc="/f58",
            )

        else:
            return render_template(
                "Questions.html",
                Question="is the cars battery light comes on ?",
                inc="/f59",
            )

    @app.route("/f58", methods=["POST"])
    @Rule(Fact(slowlystart=Yes))
    def f58():
        ans = request.form.get("YESNO")

        return render_template(
            "Questions.html",
            Question="is the cars battery light comes on ?",
            inc="/f59",
        )

    @app.route("/f59", methods=["POST"])
    @Rule(Fact(slowlystart=No))
    def f59():
        ans = request.form.get("YESNO")

        eng.declare(Fact(batteryLight=ans))
        if ans == "y":
            return render_template(
                "Questions.html",
                Question="is the headlight are dim ?",
                inc="/f60",
            )

        else:
            return render_template(
                "Result.html",
                Results="Your problem contains overlapping faults and should be presented to a specialist directly in order to accurately describe your problem ",
            )

    @app.route("/f60", methods=["POST"])
    @Rule(Fact(batteryLight=Yes))
    def f60():
        ans = request.form.get("YESNO")

        eng.declare(Fact(DimHightLight=ans))
        if ans == "y":
            return render_template(
                "Questions.html",
                Question="is the radio or other electronis don't work ?",
                inc="/f61",
            )

        else:
            return render_template(
                "Result.html",
                Results="Your problem contains overlapping faults and should be presented to a specialist directly in order to accurately describe your problem ",
            )

    @app.route("/f61", methods=["POST"])
    @Rule(Fact(DimHightLight=Yes))
    def f61():
        ans = request.form.get("YESNO")

        eng.declare(Fact(ERadio=ans))
        if ans == "y":
            return render_template(
                "Questions.html",
                Question="is thebattery leaks acid ?",
                inc="/f62",
            )

        else:
            return render_template(
                "Result.html",
                Results="Your problem contains overlapping faults and should be presented to a specialist directly in order to accurately describe your problem ",
            )

    @app.route("/f62", methods=["POST"])
    @Rule(Fact(ERadio=Yes))
    def f62():
        ans = request.form.get("YESNO")

        eng.declare(Fact(leaksAcid=ans))
        if ans == "y":
            return render_template(
                "Result.html",
                Results="your problem is might be en the battery ",
            )

        else:
            return render_template(
                "Result.html",
                Results="Your problem contains overlapping faults and should be presented to a specialist directly in order to accurately describe your problem ",
            )

    if __name__ == "__main__":
        app.run(debug=True, host="0.0.0.0", port=500)
