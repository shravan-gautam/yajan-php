COUNTER_FILE="$2/scn/$1.scncounter"
if [[ -r $COUNTER_FILE ]] ; then
   COUNT=$(<$COUNTER_FILE)
else
   COUNT=0
fi
let COUNT=$COUNT+1
#Increment counter and save to file 
echo $(( $COUNT )) > $COUNTER_FILE
cat $COUNTER_FILE
chmod 777 $COUNTER_FILE
