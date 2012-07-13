killall -9 java

CLASSPATH=$(pwd)"/drivers.jar:drivers.jar";
export CLASS_PATH=$CLASSPATH

JAVA_OPTS="-Dphp.java.bridge.daemon=true"

java -classpath $CLASSPATH $JAVA_OPTS -jar JavaBridge.jar SERVLET:8787 5 /tmp/php-java-bridge.log_$USER &
echo $! > /tmp/javabridge_native_$USER.pid

