while :
do
HOST=google.com

ping -w 3 $HOST 1>/storage/emulated/0/Android/null 2>/storage/emulated/0/Android/null
SUCCESS=$?

if [ $SUCCESS -eq 0 ]
then
echo "ok"
clear
else
settings put global airplane_mode_on 1
am broadcast -a android.intent.action.AIRPLANE_MODE --ez state true
sleep 1
settings put global airplane_mode_on 0
am broadcast -a android.intent.action.AIRPLANE_MODE --ez state true
fi
sleep 7
done